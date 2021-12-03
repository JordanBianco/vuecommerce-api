<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Http\Requests\VerifyCouponRequest;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function index()
    {
        $search = request('search', '');
        $sort = request('sort', 'id');
        $dir = request('dir', 'asc');
        $perPage = request('perPage', 10);

        return CouponResource::collection(
            Coupon::withSearch($search)
                ->orderBy($sort, $dir)
                ->paginate($perPage)
        );
    }

    public function show(Coupon $coupon)
    {
        return new CouponResource($coupon);
    }

    public function store(StoreCouponRequest $request)
    {
        $coupon = Coupon::create([
            'code' => $request->code,
            'description' => $request->description,
            'discount' => $request->discount,
        ]);

        return new CouponResource($coupon);
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $updated = $coupon->update([
            'code' => $request->code,
            'description' => $request->description,
            'discount' => $request->discount,
        ]);

        if (!$updated) {
            return $this->error();
        } else {
            return $this->success();
        }
    }

    public function destroy(Coupon $coupon)
    {
        $deleted = $coupon->delete();

        if (!$deleted) {
            return $this->error();
        } else {
            return $this->success();
        }
    }

    public function verify(VerifyCouponRequest $request)
    {
        $this->isFirstPurchase($request->coupon);

        $coupon = Coupon::where(DB::raw('BINARY `code`'), $request->coupon)->first();

        if ($coupon) {
            return new CouponResource($coupon);
        } else {
            throw ValidationException::withMessages([
                'coupon' => ['Questo codice sconto non esiste']
            ]);
        }
    }

    // Controllo che sia il primo acquisto
    protected function isFirstPurchase($coupon)
    {
        if (auth()->user()->orders()->count() >= 1 && $coupon === 'FIRST-20') {
            throw ValidationException::withMessages([
                'coupon' => ['Questo sconto non è più valido']
            ]);
        }
    }
}
