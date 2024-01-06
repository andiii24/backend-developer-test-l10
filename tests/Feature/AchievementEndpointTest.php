<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AchievementUnlocked;
use App\Models\User;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AchievementEndpointTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function testUserAchievementsEndpoint()
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Scenario 1: User with Unlocked Achievements
        AchievementUnlocked::factory()->create(['user_id' => $user->id, 'achievement_name' => 'First Lesson Watched']);
        AchievementUnlocked::factory()->create(['user_id' => $user->id, 'achievement_name' => 'First Comment Written']);

        // Scenario 2: User with No Achievements

        // Scenario 3: User Close to Next Badge
        AchievementUnlocked::factory()->count(3)->create(['user_id' => $user->id, 'achievement_name' => '5 Lessons Watched']);

        // Scenario 4: User with Maximum Achievements
        $achievements = [
            'First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', '25 Lessons Watched',
            '50 Lessons Watched', 'First Comment Written', '3 Comments Written', '5 Comments Written',
            '10 Comments Written', '20 Comments Written',
        ];
        foreach ($achievements as $achievement) {
            AchievementUnlocked::factory()->create(['user_id' => $user->id, 'achievement_name' => $achievement]);
        }

        // Make a GET request to the achievements endpoint route
        $response = $this->get('/users/' . $user->id . '/achievements');

        // Assert the response structure and expected values based on each scenario
        $response->assertStatus(200)
        ->assertJson([
            'unlocked_achievements' => [], // Expect an empty array for unlocked achievements
            'next_available_achievements' => [
                // Expect all available achievements to be shown as next available
                'First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched',
                'First Comment Written', '3 Comments Written', '5 Comments Written',
            ],
            'current_badge' => 'Beginner', // Expect 'Beginner' badge for a user with no achievements
            'next_badge' => 'Intermediate', // Expect 'Intermediate' as the next badge
            'remaining_to_unlock_next_badge' => 4, // Expect 4 achievements needed for 'Intermediate' badge
        ]);
    }
}
