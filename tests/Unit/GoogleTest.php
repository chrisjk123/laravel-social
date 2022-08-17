<?php

namespace Chriscreates\Social\Tests\Unit;

use Chriscreates\Social\Events\SocialAttempt;
use Chriscreates\Social\Events\SocialFound;
use Chriscreates\Social\Tests\TestCase;
use Chriscreates\Social\Tests\TestClasses\TestAuthModel;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;

class GoogleTest extends TestCase
{
    use WithFaker;

    private $fakeAuthData = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeAuthData = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
        ];
    }

    /** @test */
    public function it_can_log_an_existing_user_in()
    {
        Event::fake();

        $abstractUser = Mockery::mock(SocialiteUser::class);

        TestAuthModel::create($this->fakeAuthData);

        $this->assertDatabaseHas((new TestAuthModel)->getTable(), $this->fakeAuthData);

        $abstractUser
        ->shouldReceive('getId')
        ->andReturn(rand())
        ->shouldReceive('getName')
        ->andReturn($this->fakeAuthData['name'])
        ->shouldReceive('getEmail')
        ->andReturn($this->fakeAuthData['email']);

        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);

        $response = $this->get(route('auth.provider.callback', ['provider' => 'google']));

        $response->assertStatus(303);

        $this->assertEquals(1, TestAuthModel::count());

        Event::assertDispatched(SocialAttempt::class);
        Event::assertDispatched(SocialFound::class);
    }

    /** @test */
    public function it_can_register_an_account_if_one_doesnt_exist_already()
    {
        Event::fake();

        $abstractUser = Mockery::mock(SocialiteUser::class);

        $abstractUser
        ->shouldReceive('getId')
        ->andReturn(rand())
        ->shouldReceive('getName')
        ->andReturn($this->fakeAuthData['name'])
        ->shouldReceive('getEmail')
        ->andReturn($this->fakeAuthData['email']);

        Socialite::shouldReceive('driver->user')->andReturn($abstractUser);

        $response = $this->get(route('auth.provider.callback', ['provider' => 'google']));

        $response->assertStatus(303);

        $this->assertDatabaseHas((new TestAuthModel)->getTable(), $this->fakeAuthData);

        Event::assertDispatched(SocialAttempt::class);
    }
}
