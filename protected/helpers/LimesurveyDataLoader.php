<?php
declare(strict_types=1);

namespace prime\helpers;

use Carbon\Carbon;
use prime\models\ar\Response;
use prime\models\ar\Workspace;
use function iter\filter;
use function iter\toArrayWithKeys;

class LimesurveyDataLoader
{

    public function loadData(
        array $data,
        Workspace $workspace,
        Response $response
    ): void {
        $data = toArrayWithKeys(filter(function ($value) {
            return $value !== "" && $value !== null;
        }, $data));
        $response->workspace_id = $workspace->id;
        $response->survey_id = $workspace->project->base_survey_eid;
        $response->id = (int) $data['id'] ?? null;

        if (isset($data['Update'])) {
            $response->date = Carbon::createFromFormat('Y-m-d H:i:s', $data['Update'])->format('Y-m-d');
        }
        $response->hf_id = $data['UOID'] ?? null;
        // Remove some keys from the data.
        unset(
            $data['submitdate'],
            $data['ipaddr'],
            $data['startdate'],
            $data['datestamp'],
            $data['startlanguage'],
            $data['id'],
            $data['token'],
            $data['Update'],
            $data['lastpage'],
            $data['UOID']
        );

        // Transform arrays.
        $transformed = [];
        foreach ($data as $key => $value) {
            if (preg_match('/(.+)\[(\d+)]$/', $key, $matches)) {
                $transformed[$matches[1]][$matches[2]] = $value;
            } elseif (preg_match('/(.+)\[(\w+)_(\w+)]$/', $key, $matches)) {
                // Question with subquestions on 2 axes.
                $transformed[$matches[1]][$matches[2]][$matches[3]] = $value;
            } elseif (preg_match('/(.+)\[other]$/', $key, $matches)) {
                // Other is special; it is always a text question.
                $transformed[$matches[1] . 'other'] = $value;
            } elseif (preg_match('/(.+)\[comment]$/', $key, $matches)) {
                // Other is special; it is always a text question.
                $transformed[$matches[1] . 'comment'] = $value;
            } elseif (preg_match('/(.+)\[([a-zA-Z0-9]+)]$/', $key, $matches)) {
                $transformed[$matches[1]][$matches[2]] = $value;
            } else {
                $transformed[$key] = $value;
            }
        }

        ksort($transformed);
        // Recurse 1 level.
        foreach ($transformed as $key => &$value) {
            if (is_array($value)) {
                ksort($value);
            }
        }
        $response->data = $transformed;
    }
}
