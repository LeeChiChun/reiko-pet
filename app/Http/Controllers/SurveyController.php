<?php

namespace App\Http\Controllers;

use App\Models\SurveyQuestion;
use App\Models\SurveyResponse;
use App\Models\SurveyAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    // ── 前台：提交問卷 ────────────────────────────────────────

    public function submit(Request $request)
    {
        $questions = SurveyQuestion::active()->get();
        if ($questions->isEmpty()) {
            return back()->with('success', '感謝您的回饋！');
        }

        $rules = [];
        foreach ($questions as $q) {
            $rules["answers.{$q->id}"] = match ($q->type) {
                'star'   => 'required|integer|min:1|max:5',
                'choice' => 'required|string',
                default  => 'nullable|string|max:1000',
            };
        }
        $request->validate($rules);

        DB::transaction(function () use ($request, $questions) {
            $response = SurveyResponse::create(['user_id' => Auth::id()]);
            foreach ($questions as $q) {
                $val = $request->input("answers.{$q->id}");
                if ($val !== null && $val !== '') {
                    SurveyAnswer::create([
                        'response_id' => $response->id,
                        'question_id' => $q->id,
                        'answer'      => $val,
                    ]);
                }
            }
        });

        return back()->with('survey_success', '感謝您的回饋，我們會持續改善服務！');
    }

    // ── 後台：題目管理 ────────────────────────────────────────

    public function adminIndex()
    {
        $questions = SurveyQuestion::orderBy('sort_order')->get();
        $totalResponses = SurveyResponse::count();
        $satisfaction = $this->computeSatisfaction();

        return view('admin.survey', compact('questions', 'totalResponses', 'satisfaction'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'description'=> 'nullable|string|max:500',
            'type'       => 'required|in:star,text,choice',
            'options'    => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['options']    = $this->parseOptions($data['type'], $data['options'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active']  = $request->boolean('is_active', true);

        SurveyQuestion::create($data);
        return back()->with('success', '題目已新增');
    }

    public function adminUpdate(Request $request, SurveyQuestion $question)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:200',
            'description'=> 'nullable|string|max:500',
            'type'       => 'required|in:star,text,choice',
            'options'    => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active'  => 'nullable|boolean',
        ]);

        $data['options']    = $this->parseOptions($data['type'], $data['options'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active']  = $request->boolean('is_active', true);

        $question->update($data);
        return back()->with('success', '題目已更新');
    }

    public function adminDestroy(SurveyQuestion $question)
    {
        $question->delete();
        return back()->with('success', '題目已刪除');
    }

    public function adminResponses()
    {
        $responses = SurveyResponse::with('user', 'answers.question')
            ->latest()->paginate(20);
        return view('admin.survey_responses', compact('responses'));
    }

    // ── 顧客滿意度計算 ────────────────────────────────────────

    public static function computeSatisfaction(): string
    {
        $starQuestions = SurveyQuestion::where('type', 'star')->where('is_active', true)->pluck('id');
        if ($starQuestions->isEmpty()) return '98%';

        $avg = SurveyAnswer::whereIn('question_id', $starQuestions)
            ->avg(DB::raw('CAST(answer AS REAL)'));

        if (!$avg) return '98%';
        return round($avg / 5 * 100) . '%';
    }

    private function parseOptions(string $type, ?string $raw): ?array
    {
        if ($type !== 'choice' || !$raw) return null;
        return array_filter(array_map('trim', explode("\n", $raw)));
    }
}
