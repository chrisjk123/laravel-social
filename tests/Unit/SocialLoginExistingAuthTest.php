<?php

namespace Chriscreates\Social\Tests\Unit;

use Chriscreates\Social\Events\SocialAttempt;
use Chriscreates\Social\Events\SocialFound;
use Chriscreates\Social\Tests\TestCase;
use Chriscreates\Social\Tests\TestClasses\TestAuthModel;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;

class SocialLoginExistingAuthTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_log_an_existing_user_in()
    {
        Event::fake();

        $this->assertEquals(0, TestAuthModel::count());

        foreach ($this->providers as $provider) {
            $authData = [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
            ];

            TestAuthModel::create($authData);

            $this->assertDatabaseHas((new TestAuthModel)->getTable(), $authData);

            $abstractUser = Mockery::mock(SocialiteUser::class);

            $abstractUser
            ->shouldReceive('getId')
            ->andReturn(rand())
            ->shouldReceive('getName')
            ->andReturn($authData['name'])
            ->shouldReceive('getEmail')
            ->andReturn($authData['email']);

            $socialiteProvider = Mockery::mock(Provider::class);
            $socialiteProvider->shouldReceive('user')->andReturn($abstractUser);

            Socialite::shouldReceive('driver')->with($provider)->andReturn($socialiteProvider);

            $response = $this->get(route('auth.provider.callback', ['provider' => $provider]));

            $response->assertStatus(303);
        }

        $providersCount = count($this->providers);

        Event::assertDispatched(SocialAttempt::class, $providersCount);
        Event::assertDispatched(SocialFound::class, $providersCount);
        Event::assertNotDispatched(Registered::class);
        Event::assertDispatched(Authenticated::class, $providersCount);

        $this->assertEquals($providersCount, TestAuthModel::count());
    }
}
