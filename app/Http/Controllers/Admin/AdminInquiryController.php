<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductInquiry;
use Illuminate\Http\Request;

class AdminInquiryController extends Controller
{
    public function index()
    {
        $inquiries = ProductInquiry::with('product')->latest()->paginate(15);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(int $id)
    {
        $inquiry = ProductInquiry::with('product')->findOrFail($id);
        if ($inquiry->status === 'new') {
            $inquiry->update(['status' => 'read']);
        }
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function destroy(int $id)
    {
        $inquiry = ProductInquiry::findOrFail($id);
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted successfully.');
    }
}
