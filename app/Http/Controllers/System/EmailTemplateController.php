<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailTemplateRequest;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of email templates
     */
    public function index(): View
    {
        $templates = EmailTemplate::paginate(15);

        return view('system.email.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new email template
     */
    public function create(): View
    {
        return view('system.email.templates.create');
    }

    /**
     * Store a newly created email template
     */
    public function store(EmailTemplateRequest $request): RedirectResponse
    {
        EmailTemplate::create($request->validated());

        return redirect()->route('system.email.templates.index')
            ->with('success', 'E-posta şablonu başarıyla oluşturuldu.');
    }

    /**
     * Show the form for editing the specified email template
     */
    public function edit(EmailTemplate $template): View
    {
        return view('system.email.templates.edit', compact('template'));
    }

    /**
     * Update the specified email template
     */
    public function update(EmailTemplateRequest $request, EmailTemplate $template): RedirectResponse
    {
        $template->update($request->validated());

        return redirect()->route('system.email.templates.index')
            ->with('success', 'E-posta şablonu başarıyla güncellendi.');
    }

    /**
     * Remove the specified email template
     */
    public function destroy(EmailTemplate $template): RedirectResponse
    {
        $template->delete();

        return redirect()->route('system.email.templates.index')
            ->with('success', 'E-posta şablonu başarıyla silindi.');
    }
}
