
                            <div class="card card-body border pt-sm-4 pt-0 px-4 rounded-4">
                                <div class="mt-sm-0 my-4 text-center">
                                    <h1 class="card-title fw-bolder"><span class="adrm-text-green d-inline">Mes paiements</span></h1>
                                </div>

@if (!empty($items))
                                <div id="dataList" class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="text-center bg-secondary">
                                                <th class="fw-bold">Offres commandées</th>
                                                <th class="fw-bold">Numéro de commande</th>
                                                <th class="fw-bold">Montant</th>
                                                <th class="fw-bold">Livraison effectuée ?</th>
                                            </tr>
                                        </thead>

                                        <tbody>
    @foreach ($items as $item)
                                            <tr>
                                                <td class="text-center">
                                                    <ul>
        @foreach ($item['cart']['customer_orders'] as $order)
                                                        <li>{{ $order['product']['product_name'] }}</li>
        @endforeach
                                                    </ul>
                                                </td>
                                                <td class="text-center">{{ $item['order_number'] }}</td>
                                                <td class="text-center">{{ $item['converted_amount'] . ' ' . $item['readable_currency'] }}</td>
                                                <td class="text-center">
                                                    <h3>
        @if ($item['cart']['is_delivered'] == 1)
                                                        <span class="badge text-bg-danger">OUI</span>
        @else
                                                        <span class="badge text-bg-success">NON</span>
        @endif
                                                    </h3>
                                                </td>
                                            </tr>
    @endforeach
                                        </tbody>
                                    </table>

                                    <table class="table table-bordered mt-3">
                                        <tbody>
                                            <tr>
                                                <td colspan="4" class="text-end">
                                                    <strong>{{ 'TOTAL : ' . $logged_in_user['readable_unpaid_cart_total'] . ' ' . $logged_in_user['readable_currency'] }}</strong>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="mt-4 text-end">
                                        <button class="btn btn-lg adrm-btn-red rounded-pill" data-bs-toggle="modal" data-bs-target="#payModal">Effectuer le paiement</button>
                                    </div>
                                </div>
@else
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <p class="mb-0"><i class="bi bi-cart3 fs-1"></i></p>
                                    <p class="mb-0">La liste est vide</p>
                                </div>
@endif
                            </div>
