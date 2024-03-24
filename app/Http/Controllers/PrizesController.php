<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\Prize;
use App\Http\Requests\PrizeRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;




class PrizesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $prizes = Prize::all();
        return view('prizes.index', ['prizes' => $prizes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        // Calculate total probability sum of all prizes
        $totalProbability = Prize::sum('probability');
    
        // Calculate remaining probability needed to reach 100%
        $remainingProbability = 100 - $totalProbability;
    
        return view('prizes.create', [
            'totalProbability' => $totalProbability,
            'remainingProbability' => $remainingProbability,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PrizeRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PrizeRequest $request)
    {
        $prize = new Prize;
        $prize->title = $request->input('title');
        $prize->probability = floatval($request->input('probability'));
        
        //  probability sum of all prizes
        $totalProbability = Prize::sum('probability');
        
        // Calculate remaining probability needed to reach 100%
        $remainingProbability = 100 - $totalProbability;

        // Check if the entered probability exceeds the remaining probability
        if ($request->input('probability') > $remainingProbability) {
            return back()->with('error', "Probability field must not be greater than $remainingProbability%");
        }

        $prize->save();

        return redirect()->route('prizes.index')->with('success', 'Prize added successfully!');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $prize = Prize::findOrFail($id);
        return view('prizes.edit', ['prize' => $prize]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PrizeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(PrizeRequest $request, $id)
    {
        $prize = Prize::findOrFail($id);
        $prize->title = $request->input('title');
        $prize->probability = floatval($request->input('probability'));
            // Calculate total probability sum of all prizes excluding the current prize being updated
            $totalProbability = Prize::where('id', '!=', $id)->sum('probability');

            // Calculate remaining probability needed to reach 100%
            $remainingProbability = 100 - $totalProbability;
    
            // Check if the entered probability exceeds the remaining probability
            if ($request->input('probability') > $remainingProbability) {
                return back()->with('error', "Probability field must not be greater than $remainingProbability%");
            }
    
            $prize->save();
    
            return redirect()->route('prizes.index')->with('success', 'Prize updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $prize = Prize::findOrFail($id);
        $prize->delete();

        return to_route('prizes.index');
    }


    public function simulate(Request $request)
    {
        // for ($i = 0; $i < $request->number_of_prizes ?? 10; $i++) {
        //     Prize::nextPrize();
        // }
     
        // return redirect()->route('prizes.index')->with('success', 'Simulation completed successfully!');
        // // return to_route('prizes.index');

        $numberOfPrizes = $request->number_of_prizes ?? 10;
        // Output the number of prizes to be simulated
        echo "Simulating $numberOfPrizes prizes...\n";
    
        // Iterate through the number of prizes to simulate
        for ($i = 0; $i < $numberOfPrizes; $i++) {
            // Call the nextPrize method to select the next prize
            $prize = Prize::nextPrize();
            // Output the randomly selected prize
            echo "Prize $i: " . $prize->title . " (Probability: " . $prize->probability . ")\n";
        }
    
        // Output simulation complete message
        echo "Simulation completed successfully!\n";
    
        // Redirect back to the prizes index page
        return redirect()->route('prizes.index')->with('success', 'Simulation completed successfully!');
    }

    public function reset()
    {
        // TODO : Write logic here
        Prize::query()->update(['quantity' => 0]);

        // Redirect back to the prizes index page
        return redirect()->route('prizes.index')->with('success', 'Prizes reset successfully!');
    }

    
}
