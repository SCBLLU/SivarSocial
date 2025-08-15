<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement([
            Notification::TYPE_FOLLOW,
            Notification::TYPE_LIKE,
            Notification::TYPE_COMMENT
        ]);

        $users = User::pluck('id')->toArray();
        $userId = $this->faker->randomElement($users);
        $fromUserId = $this->faker->randomElement(array_diff($users, [$userId]));

        $data = [];
        $postId = null;

        if ($type === Notification::TYPE_FOLLOW) {
            $fromUser = User::find($fromUserId);
            $data = [
                'follower_username' => $fromUser->username,
                'follower_name' => $fromUser->name,
                'follower_image' => $fromUser->imagen
            ];
        } else {
            $posts = Post::where('user_id', $userId)->pluck('id')->toArray();
            if (!empty($posts)) {
                $postId = $this->faker->randomElement($posts);
                $post = Post::find($postId);
                $fromUser = User::find($fromUserId);

                $data = [
                    'liker_username' => $fromUser->username,
                    'liker_name' => $fromUser->name,
                    'liker_image' => $fromUser->imagen,
                    'post_title' => $post->titulo ?? '',
                    'post_image' => $post->imagen ?? null
                ];

                if ($type === Notification::TYPE_COMMENT) {
                    $data['comment_preview'] = $this->faker->sentence(8);
                }
            }
        }

        return [
            'user_id' => $userId,
            'from_user_id' => $fromUserId,
            'type' => $type,
            'post_id' => $postId,
            'data' => $data,
            'read_at' => $this->faker->optional(0.3)->dateTimeBetween('-1 week', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-2 weeks', 'now'),
        ];
    }

    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'read_at' => null,
            ];
        });
    }

    public function follow()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Notification::TYPE_FOLLOW,
                'post_id' => null,
            ];
        });
    }

    public function like()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Notification::TYPE_LIKE,
            ];
        });
    }

    public function comment()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => Notification::TYPE_COMMENT,
            ];
        });
    }
}
