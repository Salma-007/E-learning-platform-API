<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Cashier;

class PaymentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/V2/payments/checkout",
     *     summary="Démarrer un paiement",
     *     tags={"Payments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"course_id", "payment_type"},
     *             @OA\Property(property="course_id", type="integer", example=1),
     *             @OA\Property(property="payment_type", type="string", enum={"course", "subscription"}, example="course")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Session de paiement créée",
     *         @OA\JsonContent(
     *             @OA\Property(property="checkout_url", type="string", example="https://checkout.stripe.com/pay/...")
     *         )
     *     )
     * )
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'payment_type' => 'required|in:course,subscription'
        ]);

        $user = Auth::user();
        $course = Course::find($request->course_id);
        $price = $course->price * 100; // Convertir en centimes

        if ($request->payment_type === 'subscription') {
            // Créer un abonnement
            $checkout = $user->newSubscription('default', $this->getStripePriceId($course))
                ->checkout([
                    'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('payment.cancel'),
                    'metadata' => [
                        'course_id' => $course->id,
                        'user_id' => $user->id,
                        'type' => 'subscription'
                    ],
                ]);
        } else {
            // Paiement unique
            $checkout = $user->checkoutCharge(
                $price,
                "Achat du cours: {$course->name}",
                1,
                [
                    'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('payment.cancel'),
                    'metadata' => [
                        'course_id' => $course->id,
                        'user_id' => $user->id,
                        'type' => 'course'
                    ],
                ]
            );
        }

        return response()->json([
            'checkout_url' => $checkout->url
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/V2/payments/status/{id}",
     *     summary="Statut du paiement",
     *     tags={"Payments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du paiement ou de la session",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut du paiement",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="paid"),
     *             @OA\Property(property="payment", type="object")
     *         )
     *     )
     * )
     */
    public function paymentStatus($id)
    {
        $user = Auth::user();
        
        try {
            $session = Cashier::stripe()->checkout->sessions->retrieve($id);
            
            if ($session->payment_status === 'paid') {
                return response()->json([
                    'status' => 'paid',
                    'payment' => $session
                ]);
            }
            
            return response()->json([
                'status' => $session->payment_status,
                'payment' => $session
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/V2/payments/history",
     *     summary="Historique des paiements",
     *     tags={"Payments"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des paiements",
     *         @OA\JsonContent(
     *             @OA\Property(property="payments", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function paymentHistory()
    {
        $user = Auth::user();
        
        $payments = $user->payments()
            ->with(['course'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $invoices = $user->invoices()->map(function ($invoice) {
            return [
                'id' => $invoice->id,
                'amount' => $invoice->total / 100,
                'date' => $invoice->date()->toDateTimeString(),
                'status' => $invoice->status,
                'type' => 'subscription',
                'download_url' => $invoice->invoice_pdf
            ];
        });
        
        return response()->json([
            'payments' => $payments,
            'invoices' => $invoices
        ]);
    }

    protected function getStripePriceId(Course $course)
    {
        // Implémentez la logique pour créer/get un prix Stripe
        // Par exemple:
        $stripeProduct = \Stripe\Product::create([
            'name' => $course->name,
            'type' => 'service',
        ]);

        $stripePrice = \Stripe\Price::create([
            'product' => $stripeProduct->id,
            'unit_amount' => $course->price * 100,
            'currency' => 'eur',
            'recurring' => ['interval' => 'month'],
        ]);

        return $stripePrice->id;
    }
}