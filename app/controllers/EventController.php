<?php

declare(strict_types=1);

final class EventController extends BaseController
{
    public function index(): void
    {
        $event = new EventItem();
        $this->view('events/index', [
            'events'   => $event->upcoming(),
            'calendar' => $event->calendar(),
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate(['title' => 'Title', 'starts_at' => 'Start date']);
        if ($errors) {
            flash('error', 'Title and start date are required.');
            redirect('/events');
        }

        (new EventItem())->createEvent([
            'user_id'     => auth_user()['id'],
            'title'       => $this->input('title'),
            'description' => $this->input('description'),
            'category'    => $this->input('category', 'general'),
            'location'    => $this->input('location'),
            'starts_at'   => $this->input('starts_at'),
            'ends_at'     => $this->input('ends_at'),
        ]);
        flash('success', 'Event created.');
        redirect('/events');
    }

    public function delete(): void
    {
        (new EventItem())->delete((int) $this->input('id'));
        flash('success', 'Event deleted.');
        redirect('/events');
    }
}
