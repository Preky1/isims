<?php

declare(strict_types=1);

final class FaqController extends BaseController
{
    public function index(): void
    {
        $this->view('faqs/index', [
            'faqs'     => (new Faq())->all($_GET['q'] ?? '', $_GET['category'] ?? ''),
            'editFaq'  => isset($_GET['edit']) ? (new Faq())->find((int) $_GET['edit']) : null,
        ]);
    }

    public function store(): void
    {
        $errors = $this->validate(['question' => 'Question', 'answer' => 'Answer']);
        if ($errors) {
            flash('error', 'Question and answer are required.');
            redirect('/faqs');
        }

        (new Faq())->create([
            'user_id'  => auth_user()['id'],
            'category' => $this->input('category', 'general'),
            'question' => $this->input('question'),
            'answer'   => $this->input('answer'),
        ]);
        flash('success', 'FAQ added.');
        redirect('/faqs');
    }

    public function update(): void
    {
        $id = (int) $this->input('id');
        (new Faq())->update($id, [
            'category' => $this->input('category', 'general'),
            'question' => $this->input('question'),
            'answer'   => $this->input('answer'),
        ]);
        flash('success', 'FAQ updated.');
        redirect('/faqs');
    }

    public function delete(): void
    {
        (new Faq())->delete((int) $this->input('id'));
        flash('success', 'FAQ deleted.');
        redirect('/faqs');
    }
}
