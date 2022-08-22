<?php

namespace Chriscreates\Social\Tests\Unit;

use Chriscreates\Social\Events\SocialAttempt;
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

class SocialRegisterNewAuthInTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function it_can_register_a_new_auth_if_one_doesnt_exist_already()
    {
        Event::fake();

        $this->assertEquals(0, TestAuthModel::count());

        foreach ($this->providers as $key => $provider) {
            $authData = [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
            ];

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

            $response = $this->get(route('auth.provider.callback', compact('provider')));

            $response->assertStatus(303);

            $this->assertDatabaseHas((new TestAuthModel)->getTable(), $authData);
        }

        $providersCount = count($this->providers);

        Event::assertDispatched(SocialAttempt::class, $providersCount);
        Event::assertNotDispatched(SocialFound::class, $providersCount);
        Event::assertNotDispatched(Registered::class, $providersCount);
        Event::assertDispatched(Authenticated::class, $providersCount);

        $this->assertEquals($providersCount, TestAuthModel::count());
    }
}
