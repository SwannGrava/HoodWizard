<?php
// src/Config/TextAlign.php
namespace App\Enum;

enum State: string
{
    case Valid = 'valider';
    case StandBy = 'en attente';
    case Reject = 'rejeter';
}
?>