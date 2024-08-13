<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Post;

class NewPostMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public Post $post;

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New post is here!',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-post',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
