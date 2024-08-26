<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * DRY: Find a client by ID or return a JSON error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function findClientOrFail($id)
    {
        $client = Client::find($id);

        if (! $client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        return $client;
    }

    /**
     * List all clients.
     */
    public function list(): JsonResponse
    {
        $clients = Client::orderBy('name', 'asc')->get();

        return response()->json(['success' => $clients], $this->httpStatusOk);
    }

    /**
     * Create a new client.
     */
    public function create_client(Request $request): JsonResponse
    {
        $validator = $this->validateClient($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $client = Client::create($request->only(['name', 'contact_name', 'contact_email', 'contact_phone']));

        return response()->json(['success' => $client], $this->httpStatusOk);
    }

    /**
     * Get a specific client.
     */
    public function get_client($id): JsonResponse
    {
        $client = $this->findClientOrFail($id);

        // If $client is a JsonResponse (i.e., the client wasn't found), return it immediately.
        if ($client instanceof \Illuminate\Http\JsonResponse) {
            return $client;
        }

        return response()->json(['success' => $client], $this->httpStatusOk);
    }

    /**
     * Update a specific client.
     */
    public function update_client(Request $request, $id): JsonResponse
    {
        $client = $this->findClientOrFail($id);

        // If $client is a JsonResponse (i.e., the client wasn't found), return it immediately.
        if ($client instanceof \Illuminate\Http\JsonResponse) {
            return $client;
        }

        $validator = $this->validateClient($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        if ($client instanceof Client) {
            $client->update($request->only(['name', 'contact_name', 'contact_email', 'contact_phone']));
        }

        return response()->json(['success' => $client], $this->httpStatusOk);
    }

    /**
     * Delete a specific client.
     */
    public function delete_client($id): JsonResponse
    {
        $client = $this->findClientOrFail($id);

        // If $client is a JsonResponse (i.e., the client wasn't found), return it immediately.
        if ($client instanceof \Illuminate\Http\JsonResponse) {
            return $client;
        }

        $client->delete();

        return response()->json(['success' => 'Client deleted successfully'], $this->httpStatusOk);
    }

    /**
     * Get projects for a specific client.
     */
    public function get_client_projects($id): JsonResponse
    {
        $client = $this->findClientOrFail($id);

        // If $client is a JsonResponse (i.e., the client wasn't found), return it immediately.
        if ($client instanceof \Illuminate\Http\JsonResponse) {
            return $client;
        }

        $projects = $client->projects;

        return response()->json(['success' => $projects], $this->httpStatusOk);
    }

    /**
     * Search clients.
     */
    public function search_client(Request $request): JsonResponse
    {
        $query = $request->input('query');

        $clients = Client::when($query, function ($q) use ($query) {
            $q->where('name', 'like', '%'.$query.'%')
                ->orWhere('contact_name', 'like', '%'.$query.'%')
                ->orWhere('contact_email', 'like', '%'.$query.'%');
        })->orderBy('name', 'asc')->get();

        return response()->json(['success' => $clients], $this->httpStatusOk);
    }

    /**
     * Validate client data.
     */
    private function validateClient(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);
    }
}
