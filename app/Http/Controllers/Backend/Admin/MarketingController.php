<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MarketingLead;
use App\Models\MarketingCampaign;
use App\Models\MarketingList;
use App\Models\Course;

class MarketingController extends Controller
{
    /**
     * Display the marketing dashboard
     */
    public function index()
    {
        // Try raw DB queries first (bypass Eloquent soft delete filtering)
        // This helps diagnose if the deleted_at column is missing
        try {
            $rawCampaigns = \DB::table('marketing_campaigns')->count();
            $rawLeads = \DB::table('marketing_leads')->count();
            $rawActiveLeads = \DB::table('marketing_leads')->where('status', 'active')->count();
            $rawInactiveLeads = \DB::table('marketing_leads')->where('status', 'inactive')->count();
        } catch (\Exception $e) {
            \Log::error('Raw DB Query Failed', ['error' => $e->getMessage()]);
            $rawCampaigns = $rawLeads = $rawActiveLeads = $rawInactiveLeads = 0;
        }
        
        // Now try Eloquent model queries
        try {
            $totalCampaigns = MarketingCampaign::count();
            $totalLeads = MarketingLead::count();
            $activeLeads = MarketingLead::where('status', 'active')->count();
            $inactiveLeads = MarketingLead::where('status', 'inactive')->count();
        } catch (\Exception $e) {
            \Log::error('Eloquent Query Failed', ['error' => $e->getMessage()]);
            // Fall back to raw counts
            $totalCampaigns = $rawCampaigns;
            $totalLeads = $rawLeads;
            $activeLeads = $rawActiveLeads;
            $inactiveLeads = $rawInactiveLeads;
        }
        
        // Get data for dropdowns
        $campaigns = MarketingCampaign::latest()->get();
        $lists = MarketingList::all();
        $courses = Course::where('published', 1)->get();
        
        // Get unique values for filters (from existing leads) - only if leads exist
        if ($totalLeads > 0) {
            $subjects = MarketingLead::select('subject')->distinct()->whereNotNull('subject')->pluck('subject');
            $grades = MarketingLead::select('grade')->distinct()->whereNotNull('grade')->pluck('grade');
            $skips = MarketingLead::select('skip')->distinct()->whereNotNull('skip')->pluck('skip');
        } else {
            $subjects = collect([]);
            $grades = collect([]);
            $skips = collect([]);
        }
        
        // Debug logging
        \Log::info('Marketing Dashboard Debug', [
            'totalCampaigns' => $totalCampaigns,
            'totalLeads' => $totalLeads,
            'activeLeads' => $activeLeads,
            'inactiveLeads' => $inactiveLeads,
            'campaigns_count' => $campaigns->count(),
            'lists_count' => $lists->count(),
            'db_connection' => config('database.default'),
            'database' => config('database.connections.mysql.database')
        ]);
        
        return view('admin.marketing.index-1', compact(
            'totalCampaigns', 
            'totalLeads', 
            'activeLeads', 
            'inactiveLeads', 
            'courses', 
            'campaigns', 
            'subjects', 
            'grades', 
            'skips', 
            'lists'
        ));
    }

    /**
     * Display leads listing
     */
    public function leads(Request $request)
    {
        $status = $request->get('status', 'all');
        
        // Build query
        $query = MarketingLead::query();
        
        // Apply status filter
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }
        
        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Apply source filter
        if ($request->has('source') && $request->source) {
            $query->where('source', $request->source);
        }
        
        // Get paginated leads
        $leads = $query->latest()->paginate(20);
        
        // Get statistics
        $totalLeads = MarketingLead::count();
        $activeLeads = MarketingLead::where('status', 'active')->count();
        $inactiveLeads = MarketingLead::where('status', 'inactive')->count();
        $convertedLeads = MarketingLead::where('status', 'converted')->count();
        
        // Get lists for dropdown
        $lists = MarketingList::all();
        
        // Get unique sources for filter
        $sources = MarketingLead::select('source')->distinct()->whereNotNull('source')->pluck('source');
        
        return view('admin.marketing.leads', compact(
            'leads', 
            'status', 
            'totalLeads', 
            'activeLeads', 
            'inactiveLeads',
            'convertedLeads',
            'lists',
            'sources'
        ));
    }

    /**
     * Display leads by list
     */
    public function list(Request $request)
    {
        $listId = $request->get('list_id');
        
        // Get all lists for dropdown
        $lists = MarketingList::all();
        
        // Get leads for selected list
        if ($listId) {
            $list = MarketingList::findOrFail($listId);
            $leads = $list->leads()->paginate(20);
        } else {
            $leads = collect([]);
        }
        
        return view('admin.marketing.list', compact('lists', 'leads', 'listId'));
    }

    /**
     * Store a new lead
     */
    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,converted',
            'grade' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:50',
            'skip' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);
        
        MarketingLead::create($validated);
        
        return redirect()->back()->withFlashSuccess('Lead added successfully');
    }

    /**
     * Store a new campaign
     */
    public function storeCampaign(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:email,sms,whatsapp',
            'status' => 'required|in:draft,active,completed',
            'audience' => 'nullable|string',
            'subject' => 'nullable|string',
            'grade' => 'nullable|string',
            'skip' => 'nullable|string',
            'list_id' => 'nullable|exists:marketing_lists,id',
            'aisensy_campaign_id' => 'nullable|string|max:255',
        ]);
        
        MarketingCampaign::create($validated);
        
        return redirect()->back()->withFlashSuccess('Campaign created successfully');
    }

    /**
     * Import leads from file
     */
    public function importLeads(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xlsx,xls|max:5120',
        ]);
        
        // TODO: Implement actual import logic using Laravel Excel or similar
        // For now, just return success
        
        return redirect()->back()->withFlashSuccess('Leads imported successfully');
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="leads-template.csv"',
        ];
        
        $columns = ['name', 'phone', 'email', 'source', 'status', 'grade', 'subject', 'skip', 'notes'];
        
        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Assign leads to a list
     */
    public function assignList(Request $request)
    {
        $validated = $request->validate([
            'list_id' => 'required|exists:marketing_lists,id',
            'lead_ids' => 'required|array',
            'lead_ids.*' => 'exists:marketing_leads,id',
        ]);
        
        $list = MarketingList::findOrFail($validated['list_id']);
        $list->leads()->syncWithoutDetaching($validated['lead_ids']);
        
        return redirect()->back()->withFlashSuccess('Leads assigned to list successfully');
    }

    /**
     * Get lead data for editing (JSON response for AJAX)
     */
    public function editLead($lead)
    {
        $lead = MarketingLead::findOrFail($lead);
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'lead' => $lead
            ]);
        }
        
        // Return view for regular requests
        return view('admin.marketing.leads-edit', compact('lead'));
    }

    /**
     * Update a lead
     */
    public function updateLead(Request $request, $lead)
    {
        $lead = MarketingLead::findOrFail($lead);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'source' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,converted',
            'grade' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:50',
            'skip' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);
        
        $lead->update($validated);
        
        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->withFlashSuccess('Lead updated successfully');
    }

    /**
     * Delete a lead
     */
    public function destroyLead($lead)
    {
        $lead = MarketingLead::findOrFail($lead);
        $lead->delete();
        
        // Return JSON for AJAX requests
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->withFlashSuccess('Lead deleted successfully');
    }
}
