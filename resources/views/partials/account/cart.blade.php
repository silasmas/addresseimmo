
                        <div class="card card-body border pt-sm-4 pt-0 px-4 rounded-4">
                            <div class="mt-sm-0 my-4 text-center">
                                <h1 class="card-title fw-bolder"><span class="adrm-text-green d-inline">Mon panier</span></h1>
                            </div>

@if (!empty($user_orders))
                            <div id="dataList" class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>
                                            <td></td>
                                            <td>Offre</td>
                                            <td>Prix unitaire</td>
                                            <td>Quantité</td>
                                            <td>Prix total</td>
                                        </th>
                                    </thead>

                                    <tbody>
	@php
		foreach ($user_orders as $item) {
			$item->converted_price = formatDecimalNumber($item->convertPriceAtThatTime($current_user['currency']), 2);
		}

		$itemsArray = $items->toArray();
	@endphp

    @foreach ($itemsArray as $item)
                                        <tr>
                                            <td>
                                                <img src="{{ count($item['product']['photos']) > 0 ? $item['product']['photos'][0]['file_url'] : asset('assets/img/undefined.png') }}" alt="{{ $item['product']['product_name'] }}">
                                            </td>
                                            <td>
                                                <a href="{{ route('product.datas', ['id' => $item['product']['id']]) }}">
                                                    {{ $item['product']['product_name'] }}
                                                </a>
                                            </td>
                                            <td>{{ $item['converted_price_at_that_time'] . ' ' . $current_user['readable_currency'] }}</td>
                                            <td>{{ $item['quantity'] }}</td>
                                            <td>{{ $item['readable_sub_total'] . ' ' . $current_user['readable_currency'] }}</td>
                                        </tr>
    @endforeach
                                    </tbody>
                                {{-- </table>

								<table class="table table-bordered"> --}}
									<tfoot>
										<tr>
											<td>{{ 'TOTAL : ' . formatDecimalNumber($current_user['unpaid_cart_total']) . ' ' . $current_user['currency'] }}</td>
										</tr>
									</tfoot>
								</table>
                            </div>
@else
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <p class="mb-0"><i class="bi bi-cart3 fs-1"></i></p>
                            <p class="mb-0">La liste est vide</p>
                        </div>
@endif
                        </div>
