<?php

namespace App\Http\Controllers;

use App\Models\AchievementUnlocked;
use App\Models\Badge;
use App\Models\User;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        // Fetch unlocked achievements by the user
        $unlockedAchievements = AchievementUnlocked::where('user_id', $user->id)->pluck('achievement_name')->toArray();

        // Define all available achievements
        $allAchievements = [
            'First Lesson Watched', '5 Lessons Watched', '10 Lessons Watched', // ... and so on
            'First Comment Written', '3 Comments Written', '5 Comments Written', // ... and so on
        ];

        // Identify next available achievements not unlocked by the user
        $nextAvailableAchievements = array_diff($allAchievements, $unlockedAchievements);

        // Determine current badge based on the number of unlocked achievements
        $achievementCount = count($unlockedAchievements);
        $badges = Badge::orderBy('numberOfAchivment')->get();

        $currentBadge = '';
        foreach ($badges as $badge) {
            if ($achievementCount < $badge->numberOfAchivment) {
                break;
            }
            $currentBadge = $badge->name;
        }
        $nextBadge = '';
        $remainingToUnlockNextBadge = 0;
        foreach ($badges as $badge) {
            if ($achievementCount < $badge->numberOfAchivment) {
                $nextBadge = $badge->name;
                $remainingToUnlockNextBadge = $badge->numberOfAchivment - $achievementCount;
                break;
            }
        }

        return response()->json([
            'unlocked_achievements' => $unlockedAchievements,
            'next_available_achievements' => array_values($nextAvailableAchievements),
            'current_badge' => $currentBadge,
            'next_badge' => $nextBadge,
            'remaing_to_unlock_next_badge' => $remainingToUnlockNextBadge,
        ]);
    }
}
