<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function addComment($body)
    {
        Comment::create([
            'body' => $body,
            'post_id' => $this->id,
        ]);
    }

    public function attachOldTagOrCreateNewAndAttach($existingTags, $tag) 
    {
        if($oldTag = $existingTags->firstWhere('name', $tag)){
            $this->tags()->attach($oldTag);
        } else {
            $newTag = new Tag;
            $newTag->name = $tag;
            if($newTag->save()){
                $this->tags()->attach($newTag);
            }
        }
    }
}
