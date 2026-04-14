$ErrorActionPreference = 'Stop'

$serviceGeneric = 'Yo logic service layer ma rakheko le controller clean rahanchha, same rule repeat hudaina, ra bug fix/test maintain garna sajilo huncha.'
$modelGeneric = 'Related records load garna ra relation-based query clean rakhna yo relation method chahinchha.'

function Get-ServiceWhy([string]$methodName) {
    $m = $methodName.ToLowerInvariant()

    if ($m -match '^(get|list|find|show|load|fetch|customerdata|staffdata|hotelownerdata)') {
        return 'Yo query rule service ma rak्दा controller slim rahanchha ra data shape sabai screen ma consistent dekhinchha.'
    }

    if ($m -match '^(create|store|save|upsert|confirm|cancel|delete|update|mark|respond|process|set)') {
        return 'Write workflow ko validation ra status change ekai thau ma rakhda data mismatch ra side-effect bug kam hunchha.'
    }

    if ($m -match '^(build|generate|prepare|compose|map|transform)') {
        return 'Output banne rule yahi method ma clear rakhda format change huda impact track garna sajilo hunchha.'
    }

    return 'Yo method ko business rule service layer ma rakhda future change garna ra test garna sajilo hunchha.'
}

function Update-ServiceFile([string]$path) {
    $original = Get-Content -Raw -Path $path
    $pattern = '(?s)/\*\*(?<doc>.*?)\*/\s*(?<sig>(public|protected|private)\s+function\s+(?<name>[A-Za-z_][A-Za-z0-9_]*)\s*\()'
    $updated = [regex]::Replace($original, $pattern, {
        param($match)
        $doc = $match.Groups['doc'].Value
        $sig = $match.Groups['sig'].Value
        $name = $match.Groups['name'].Value

        if ($doc -notmatch [regex]::Escape($serviceGeneric)) {
            return $match.Value
        }

        $why = Get-ServiceWhy $name
        $newDoc = $doc.Replace($serviceGeneric, $why)
        return '/**' + $newDoc + '*/' + "`r`n    " + $sig
    })

    if ($updated -ne $original) {
        Set-Content -Path $path -Value $updated -NoNewline
        return 1
    }

    return 0
}

function Update-ModelFile([string]$path) {
    $original = Get-Content -Raw -Path $path
    $pattern = '(?s)/\*\*(?<doc>.*?)\*/\s*(?<sig>(public|protected|private)\s+function\s+(?<name>[A-Za-z_][A-Za-z0-9_]*)\s*\()'
    $updated = [regex]::Replace($original, $pattern, {
        param($match)
        $doc = $match.Groups['doc'].Value
        $sig = $match.Groups['sig'].Value
        $name = $match.Groups['name'].Value

        if ($doc -notmatch [regex]::Escape($modelGeneric)) {
            return $match.Value
        }

        $relationName = $name
        if ($relationName.EndsWith('s')) {
            $relationName = $relationName.Substring(0, $relationName.Length - 1)
        }

        $why = "Yo relation le $relationName sanga linked data eager-load ra filter query ma safely reuse garna help garcha."
        $newDoc = $doc.Replace($modelGeneric, $why)
        return '/**' + $newDoc + '*/' + "`r`n    " + $sig
    })

    if ($updated -ne $original) {
        Set-Content -Path $path -Value $updated -NoNewline
        return 1
    }

    return 0
}

$serviceFiles = Get-ChildItem -Path 'e:\TrekAdvisor\app\Services' -Recurse -Filter '*.php' | Select-Object -ExpandProperty FullName
$modelFiles = Get-ChildItem -Path 'e:\TrekAdvisor\app\Models' -Recurse -Filter '*.php' | Select-Object -ExpandProperty FullName

$serviceUpdated = 0
foreach ($f in $serviceFiles) { $serviceUpdated += Update-ServiceFile $f }

$modelUpdated = 0
foreach ($f in $modelFiles) { $modelUpdated += Update-ModelFile $f }

"service_files=$($serviceFiles.Count) service_updated=$serviceUpdated model_files=$($modelFiles.Count) model_updated=$modelUpdated"
