<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Traits\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class ResetController extends Controller
{
    use apiresponse;
    /**
     * Reset Database and Optimize Clear
     *
     * @return JsonResponse
     */
    public function RunMigrations(): JsonResponse
    {
        // Capture the output of the Artisan commands
        $migrateOutput  = Artisan::call('migrate:fresh --seed');
        $optimizeOutput = Artisan::call('optimize:clear');

        // Check if there are any errors in the output
        if ($migrateOutput !== 0 || $optimizeOutput !== 0) {
            return $this->sendError('An error occurred while running migrations or clearing cache.', [], 500);
        }

        return $this->sendResponse([], 'Database and cache successfully reset and cleared.');
    }


    public function cacheClear(): JsonResponse
    {
        // Capture the output of the Artisan commands
        $optimizeOutput = Artisan::call('optimize:clear');

        // Check if there are any errors in the output
        if ($optimizeOutput !== 0) {
            return $this->sendError('An error occurred while clearing cache.', [], 500);
        }
        return $this->sendResponse([], 'Cache successfully cleared.');
    }

    public function dumpAutoLoad(): JsonResponse
    {
        // Capture the output and return status of the exec command
        $output = [];
        $statusCode = null;
        exec('composer dump-autoload', $output, $statusCode);

        // Check if the status code indicates success (0 is success for exec)
        if ($statusCode !== 0) {
            // Log the output for debugging purposes
            \Log::error('Composer dump-autoload error', ['output' => $output, 'status' => $statusCode]);

            // Return the error response with the message
            return $this->sendError('An error occurred while updating the autoloader.', [], 500);
        }

        // Return success response if no error occurred
        return $this->sendResponse([], 'Autoloader updated successfully.');
    }

    public function composerUpdate(): JsonResponse
    {
        // Run the composer dump-autoload command using exec
        $composerOutput = exec('composer update');

        // Check if there are any errors in the output
        if ($composerOutput !== 0) {
            return $this->sendError('An error occurred while updating composer.', [], 500);
        }

        return $this->sendResponse([], 'Composer updated successfully.');
    }


    public function comand($comand): JsonResponse
    {
        try {
            // Add the '--force' flag when in production mode
            $exitCode = Artisan::call($comand, ['--force' => true]);

            return response()->json([
                'status' => true,
                'message' => Artisan::output(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while running the command: ' . $comand,
                'error' => $e->getMessage(),
                'code' => 500,
            ]);
        }
    }
}
