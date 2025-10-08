
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
                                                <th class="fw-bold">Est livrée ?</th>
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
                                                <td>{{ $item['order_number'] }}</td>
                                                <td class="text-center">{{ $item['converted_amount'] . ' ' . $item['readable_currency'] }}</td>
                                                <td>
                                                    <h5>
        @if ($item['cart']['is_delivered'] == 1)
                                                        <span class="badge text-bg-sucess">OUI</span>
        @else
                                                        <span class="badge text-bg-danger">NON</span>
        @endif
                                                    </h5>
                                                </td>
                                            </tr>
    @endforeach
                                        </tbody>
                                    </table>
                                </div>
@else
                                <div class="d-flex justify-content-center align-items-center flex-column">
                                    <p class="mb-0"><i class="bi bi-cart3 fs-1"></i></p>
                                    <p class="mb-0">La liste est vide</p>
                                </div>
@endif
                            </div>
