$paths = @('app/Services','app/Models')
$files = foreach($p in $paths){ if(Test-Path $p){ Get-ChildItem $p -Recurse -File -Filter *.php } }

function BuildServiceWhat([string]$method){
    $m = $method.ToLower()
    switch -regex ($m) {
        'customerdata' { return 'Yo method le customer dashboard ko bookings ra stats aggregate garera return garcha.' }
        'staffdata' { return 'Yo method le staff dashboard ko booking stats, chart data, ra recent records prepare garcha.' }
        'hotelownerdata' { return 'Yo method le hotel owner dashboard ko hotels, bookings, ra revenue stats build garcha.' }
        'getstaffactivitytrend' { return 'Yo method le last 7 din ko booking activity trend calculate garcha.' }
        'gethotelownerrevenuetrend' { return 'Yo method le weekly revenue trend compute garera chart ko lagi data dincha.' }
        'checkouturl' { return 'Yo method le active eSewa checkout endpoint return garcha.' }
        'decodesuccesspayload' { return 'Yo method le eSewa success payload decode garera usable array banaucha.' }
        'verifypayloadsignature' { return 'Yo method le eSewa payload signature verify garera data tamper bhayeko chaina bhanne confirm garcha.' }
        'createcheckoutpayload' { return 'Yo method le eSewa checkout request ko signed payload build garcha.' }
        'verifytransaction' { return 'Yo method le gateway side transaction verify garera payment status trustable banaucha.' }
        'getcheckoutbooking' { return 'Yo method le checkout bela use hune trek booking load garcha.' }
        'getdisplaybooking' { return 'Yo method le payment result page ko lagi booking detail fetch garcha.' }
        'confirmbooking' { return 'Yo method le successful payment pachi booking confirm garera seat count update garcha.' }
        'authorizeowner' { return 'Yo method le current user le yo payment access garna milcha ki mildaina bhanne check garcha.' }
        'createretrysession|createretryurl' { return 'Yo method le failed/pending payment retry flow ko naya checkout link/session banaucha.' }
        'syncsuccessfulpayment' { return 'Yo method le success callback pachi payment ra booking state sync garcha.' }
        'markfailed' { return 'Yo method le failed callback pachi payment status failed/cancelled ma update garcha.' }
        'handlewebhookevent' { return 'Yo method le gateway webhook event parse garera payment state update trigger garcha.' }
        'upsert|create|store|save|handle|start' { return "Yo method le $method related business flow execute garcha." }
        'delete|remove|cancel|mark|confirm|update|sync' { return "Yo method le $method related state change safely apply garcha." }
        'list|get|find|fetch|resolve|load|paginate|make' { return "Yo method le $method related data prepare/fetch garcha." }
        default { return "Yo method le $method ko service-level kaam handle garcha." }
    }
}

function BuildServiceWhy([string]$method){
    return 'Yo logic service layer ma rakheko le controller clean rahanchha, same rule repeat hudaina, ra bug fix/test maintain garna sajilo huncha.'
}

function BuildModelWhat([string]$method){
    $m = $method.ToLower()
    switch -regex ($m) {
        '^scope' { return "Yo method le $method query scope define garcha." }
        '^get|^set' { return "Yo method le $method accessor/mutator behavior define garcha." }
        default { return "Yo relation method le model lai $method relation sanga map garcha." }
    }
}

function BuildModelWhy([string]$method){
    $m = $method.ToLower()
    if($m -match '^scope'){ return 'Reusable filters euta thau ma rakhera query code short ra consistent banauna yo scope chahinchha.' }
    if($m -match '^get|^set'){ return 'Read/write data format model level ma control garna yo accessor/mutator method chahinchha.' }
    return 'Related records load garna ra relation-based query clean rakhna yo relation method chahinchha.'
}

$updatedFiles = 0
$addedBlocks = 0

foreach($file in $files){
    $lines = [System.Collections.Generic.List[string]]::new()
    (Get-Content -LiteralPath $file.FullName) | ForEach-Object { [void]$lines.Add($_) }
    $changed = $false
    $isService = $file.FullName -match '\\app\\Services\\'

    for($i = 0; $i -lt $lines.Count; $i++){
        if($lines[$i] -match '^(\s*)(public|protected)\s+function\s+([A-Za-z_][A-Za-z0-9_]*)\s*\('){
            $indent = $matches[1]
            $method = $matches[3]
            if($method -eq '__construct'){ continue }

            $prev = $i - 1
            while($prev -ge 0 -and [string]::IsNullOrWhiteSpace($lines[$prev])){ $prev-- }
            if($prev -ge 0 -and ($lines[$prev].Trim() -eq '*/' -or $lines[$prev].Trim().StartsWith('/**'))){ continue }

            $what = if($isService){ BuildServiceWhat $method } else { BuildModelWhat $method }
            $why = if($isService){ BuildServiceWhy $method } else { BuildModelWhy $method }

            $doc = @(
                "$indent/**",
                "$indent * $what",
                "$indent *",
                "$indent * Why:",
                "$indent * $why",
                "$indent */"
            )

            for($d = $doc.Count - 1; $d -ge 0; $d--){
                $lines.Insert($i, [string]$doc[$d])
            }

            $i += $doc.Count
            $changed = $true
            $addedBlocks++
        }
    }

    if($changed){
        Set-Content -LiteralPath $file.FullName -Value $lines
        $updatedFiles++
    }
}

"files_scanned=$($files.Count)"
"files_updated=$updatedFiles"
"method_comment_blocks_added=$addedBlocks"
