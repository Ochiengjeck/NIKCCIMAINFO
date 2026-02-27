<?php

use App\Http\Controllers\MediaUploadController;
use App\Http\Controllers\MembershipCertificateController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Audit\ActivityLog;
use App\Livewire\Chatbot\ChatLogViewer;
use App\Livewire\Chatbot\FaqEditor;
use App\Livewire\Documents\DocumentApproval;
use App\Livewire\Documents\DocumentLibrary;
use App\Livewire\Documents\DocumentUploader;
use App\Livewire\Events\AttendanceReport;
use App\Livewire\Events\EventManager;
use App\Livewire\Events\RegistrationDesk;
use App\Livewire\Events\SponsorManager;
use App\Livewire\Finance\BudgetTracker;
use App\Livewire\Finance\ExpenseUploader;
use App\Livewire\Finance\InvoiceManager;
use App\Livewire\Finance\RevenueReport;
use App\Livewire\Finance\TransactionList;
use App\Livewire\Governance\DiplomaticEngagementLog;
use App\Livewire\Governance\MouRepository;
use App\Livewire\Governance\ResolutionBoard;
use App\Livewire\Governance\StrategicPlanRepository;
use App\Livewire\Governance\VotingBooth;
use App\Livewire\Kpi\ChapterComparison;
use App\Livewire\Kpi\ExecutiveDashboard;
use App\Livewire\Kpi\PillarScorecard;
use App\Livewire\Membership\ApplicationDetail;
use App\Livewire\Membership\ApplicationForm;
use App\Livewire\Membership\ApplicationList;
use App\Livewire\Membership\CategoryManager;
use App\Livewire\Membership\MemberList;
use App\Livewire\Membership\MemberProfile;
use App\Livewire\Platforms\PlatformDetail;
use App\Livewire\Platforms\PlatformManager;
use App\Livewire\Policy\NtbManager;
use App\Livewire\Policy\PolicyBriefEditor;
use App\Livewire\Policy\RooRepository;
use App\Livewire\Policy\TradeStatusDashboard;
use App\Livewire\Reports\ReportGenerator;
use App\Livewire\Settings\SmtpConfigurator;
use App\Livewire\Settings\SystemSettings;
use App\Livewire\System\ChapterManager;
use App\Livewire\System\RoleManager;
use App\Livewire\System\UserDetail;
use App\Livewire\System\UserManager;
use App\Livewire\Trade\B2bMatchmaker;
use App\Livewire\Trade\CorridorTracker;
use App\Livewire\Trade\DealPipeline;
use App\Livewire\Trade\InvestorManager;
use App\Livewire\Trade\TradeLeadBoard;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Phase 2: Membership
    Route::prefix('membership')->name('membership.')->group(function () {
        Route::get('/categories', CategoryManager::class)->name('categories');
        Route::get('/apply', ApplicationForm::class)->name('apply');
        Route::get('/applications', ApplicationList::class)->name('applications');
        Route::get('/applications/{application}', ApplicationDetail::class)->name('applications.show');
        Route::get('/members', MemberList::class)->name('members');
        Route::get('/members/{member}', MemberProfile::class)->name('members.show');
        Route::get('/members/{member}/certificate', MembershipCertificateController::class)->name('members.certificate');
    });

    // Phase 3: Trade & Investment
    Route::prefix('trade')->name('trade.')->group(function () {
        Route::get('/leads', TradeLeadBoard::class)->name('leads');
        Route::get('/deals', DealPipeline::class)->name('deals');
        Route::get('/corridors', CorridorTracker::class)->name('corridors');
        Route::get('/investors', InvestorManager::class)->name('investors');
        Route::get('/b2b', B2bMatchmaker::class)->name('b2b');
    });

    // Phase 4: Policy & Research
    Route::prefix('policy')->name('policy.')->group(function () {
        Route::get('/ntbs', NtbManager::class)->name('ntbs');
        Route::get('/briefs', PolicyBriefEditor::class)->name('briefs');
        Route::get('/roo', RooRepository::class)->name('roo');
        Route::get('/trade-status', TradeStatusDashboard::class)->name('trade-status');
    });

    // Phase 5: Finance
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/transactions', TransactionList::class)->name('transactions');
        Route::get('/invoices', InvoiceManager::class)->name('invoices');
        Route::get('/budgets', BudgetTracker::class)->name('budgets');
        Route::get('/expenses', ExpenseUploader::class)->name('expenses');
        Route::get('/revenue', RevenueReport::class)->name('revenue');
    });

    // Phase 6: Events
    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/', EventManager::class)->name('index');
        Route::get('/desk', RegistrationDesk::class)->name('desk');
        Route::get('/attendance', AttendanceReport::class)->name('attendance');
        Route::get('/sponsors', SponsorManager::class)->name('sponsors');
    });

    // Phase 7: Governance
    Route::prefix('governance')->name('governance.')->group(function () {
        Route::get('/plans', StrategicPlanRepository::class)->name('plans');
        Route::get('/mous', MouRepository::class)->name('mous');
        Route::get('/engagements', DiplomaticEngagementLog::class)->name('engagements');
        Route::get('/resolutions', ResolutionBoard::class)->name('resolutions');
        Route::get('/vote', VotingBooth::class)->name('vote');
    });

    // Phase 8: Documents
    Route::prefix('documents')->name('documents.')->group(function () {
        Route::get('/', DocumentLibrary::class)->name('index');
        Route::get('/upload', DocumentUploader::class)->name('upload');
        Route::get('/approve', DocumentApproval::class)->name('approve');
        Route::get('/{document}/download', function (\App\Models\Document $document) {
            Gate::authorize('view', $document);

            return Storage::disk('local')->download($document->file_path, $document->title);
        })->name('download');
    });

    // Phase 9: KPIs
    Route::prefix('kpi')->name('kpi.')->group(function () {
        Route::get('/', ExecutiveDashboard::class)->name('dashboard');
        Route::get('/comparison', ChapterComparison::class)->name('comparison');
        Route::get('/scorecard', PillarScorecard::class)->name('scorecard');
    });

    // Phase 10: Technical Platforms
    Route::prefix('platforms')->name('platforms.')->group(function () {
        Route::get('/', PlatformManager::class)->name('index');
        Route::get('/{platform}', PlatformDetail::class)->name('show');
    });

    // Phase 11: Email/SMTP Settings
    Route::prefix('system/settings')->name('settings.')->group(function () {
        Route::get('/smtp', SmtpConfigurator::class)->name('smtp');
        Route::get('/general', SystemSettings::class)->name('general');
    });

    // Phase 12: Chatbot
    Route::prefix('chatbot')->name('chatbot.')->group(function () {
        Route::get('/faqs', FaqEditor::class)->name('faqs');
        Route::get('/logs', ChatLogViewer::class)->name('logs');
    });

    // Phase 13: Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', ReportGenerator::class)->name('index');
    });

    // Phase 14: Audit Log
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', ActivityLog::class)->name('index');
    });

    // Phase 15: System Management
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/users', UserManager::class)->name('users');
        Route::get('/users/{user}', UserDetail::class)->name('user-detail');
        Route::get('/roles', RoleManager::class)->name('roles');
        Route::get('/chapters', ChapterManager::class)->name('chapters');
        Route::get('/settings', SystemSettings::class)->name('settings');
        Route::get('/smtp', SmtpConfigurator::class)->name('smtp');
    });

    // Media upload API — used by all JS-based uploaders across the panel
    Route::post('/media/upload', [MediaUploadController::class, 'upload'])->name('media.upload');
    Route::get('/media/{id}/download', [MediaUploadController::class, 'download'])->name('media.download');

    // Website CMS
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::get('/pages', \App\Livewire\Cms\PageEditor::class)->name('pages');
        Route::get('/news', \App\Livewire\Cms\NewsManager::class)->name('news');
        Route::get('/leadership', \App\Livewire\Cms\LeadershipManager::class)->name('leadership');
        Route::get('/media', \App\Livewire\Cms\MediaLibrary::class)->name('media');
        Route::get('/contact', \App\Livewire\Cms\ContactManager::class)->name('contact');
    });
});
