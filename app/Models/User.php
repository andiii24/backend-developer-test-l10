<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * The comments that belong to the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * The lessons that a user has access to.
     */
    public function lessons()
    {
        return $this->belongsToMany(Lesson::class);
    }

    /**
     * The lessons that a user has watched.
     */
    public function watched()
    {
        return $this->belongsToMany(Lesson::class)->wherePivot('watched', true);
    }
    public function unlockLessonAchievement()
    {
        $lessonCount = $this->watched()->count();

        $achievementNames = [
            1 => 'First Lesson Watched',
            5 => '5 Lessons Watched',
            10 => '10 Lessons Watched',
            25 => '25 Lessons Watched',
            50 => '50 Lessons Watched',
        ];

        foreach ($achievementNames as $count => $achievementName) {
            if ($lessonCount >= $count && !$this->hasUnlockedAchievement($achievementName)) {
                $this->unlockAchievement($achievementName);
            }
        }
    }

    public function unlockCommentAchievement()
    {
        $commentCount = $this->comments()->count();

        $achievementNames = [
            1 => 'First Comment Written',
            3 => '3 Comments Written',
            5 => '5 Comments Written',
            10 => '10 Comments Written',
            20 => '20 Comments Written',
        ];
        // to prevent duplicated achievement and filter out the new achievement
        foreach ($achievementNames as $count => $achievementName) {
            if ($commentCount >= $count && !$this->hasUnlockedAchievement($achievementName)) {
                $this->unlockAchievement($achievementName);
            }
        }
    }

    public function unlockAchievement($achievementName)
    {
        // Logic to unlock an achievement
        $this->unlocked_achievements()->attach([
            'name' => $achievementName,
            'user_id' => $this->id,
        ]);

        // Fire event to notify about the achievement unlock
        event(new AchievementUnlocked($achievementName, $this));
    }

    public function hasUnlockedAchievement($achievementName)
    {
        return $this->unlocked_achievements()->where('name', $achievementName)->exists();
    }

}

