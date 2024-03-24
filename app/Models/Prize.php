<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prize extends Model
{

    protected $guarded = ['id'];




    public  static function nextPrize()
    {
        // TODO: Implement nextPrize() logic here.

                // // Get total probability sum of all prizes
                // $totalProbability = self::sum('probability');

                // // Generate a random number between 0 and total probability
                // $randomNumber = rand(0, $totalProbability);
        
                // // Iterate through prizes to find the winning prize
                // $cumulativeProbability = 0;
                // foreach (self::all() as $prize) {
                //     $cumulativeProbability += $prize->probability;
                //     if ($randomNumber <= $cumulativeProbability) {
                //         // This prize wins
                //         // Update the quantity if it's not unlimited
                //         if ($prize->quantity > 0) {
                //             $prize->decrement('quantity');
                //         }
                //         break;
                //     }
                // }




                    // Get total probability sum of all prizes
    $totalProbability = self::sum('probability');

    // Generate a random number between 0 and total probability
    $randomNumber = rand(0, $totalProbability);

    // Debug: Output the total probability and random number
    echo "Total Probability: $totalProbability, Random Number: $randomNumber\n";

    // Iterate through prizes to find the winning prize
    $cumulativeProbability = 0;
    foreach (self::all() as $prize) {
        $cumulativeProbability += $prize->probability;

        // Debug: Output the cumulative probability for each prize
        echo "Prize: {$prize->title}, Cumulative Probability: $cumulativeProbability\n";

        if ($randomNumber <= $cumulativeProbability) {
            // This prize wins
            // Debug: Output the winning prize
            echo "Winning Prize: {$prize->title}\n";

            // Update the quantity if it's not unlimited
            if ($prize->quantity > 0) {
                $prize->decrement('quantity');
            }
            return $prize; // Return the winning prize
        }
    }

    // In case no prize is selected
    return null;
    }
}
