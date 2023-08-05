<?php

$srcFolder = 'src';
$includesFolder = 'src/includes';
$targetFolder = 'public';

$files = scandir($srcFolder);

foreach ($files as $file) {
    if (file_exists($srcFolder . '/' . $file) &&  is_file($srcFolder . '/' . $file)) {
        $contents = file_get_contents($srcFolder . '/' . $file);
        $lines = explode(PHP_EOL, $contents);

        $output = [];
        $vars = [];

        foreach ($lines as $line) {
            // Var
            if (strpos($line, 'var:') !== false) {
                preg_match('/<!--[\s*]var:(.*)=[\'"](.*)[\'"][\s*]-->/', $line, $matches);

                if (isset($matches[1]) && isset($matches[2])) {
                    $vars[$matches[1]] = $matches[2];
                }

                continue;
            }

            // Include
            if (strpos($line, 'include:') !== false) {
                preg_match('/<!--[\s*]include:(.*)[\s*]-->/', $line, $matches);

                if (isset($matches[1]) && file_exists($includesFolder . '/' . $matches[1]) &&  is_file($includesFolder . '/' . $matches[1])) {
                    $includeContents = file_get_contents($includesFolder . '/' . $matches[1]);
                    $includeLines = explode(PHP_EOL, $includeContents);

                    foreach ($includeLines as $includeLine) {
                        // Vars
                        preg_match_all('/<!--[\s*]var:([^->]*)[\s*]-->/', $includeLine, $includeMatches);

                        if (isset($includeMatches[1])) {
                            foreach ($includeMatches[1] as $includeMatch) {
                                $includeLine = preg_replace('/<!--[\s*]var:([^->]*)[\s*]-->/', $vars[$includeMatch] ?? '', $includeLine);
                            }
                        }

                        preg_match_all('/{{[\s*]var:([^}]*)[\s*]}}/', $includeLine, $includeMatches);

                        if (isset($includeMatches[1])) {
                            foreach ($includeMatches[1] as $includeMatch) {
                                $includeLine = preg_replace('/{{[\s*]var:([^}]*)[\s*]}}/', $vars[$includeMatch] ?? '', $includeLine);
                            }
                        }

                        $output[] = $includeLine;
                    }
                }

                continue;
            }

            $output[] = $line;
        }

        if (!is_dir($targetFolder)) {
            mkdir($targetFolder);
        }

        file_put_contents($targetFolder . '/' . $file, implode(PHP_EOL, $output));
    }
}
