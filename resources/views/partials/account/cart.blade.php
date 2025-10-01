
                        <div class="card card-body border pt-sm-4 pt-0 px-4 rounded-4">
                            <div class="mt-sm-0 my-4 text-center">
                                <h1 class="card-title fw-bolder"><span class="adrm-text-green d-inline">Mon panier</span></h1>
                            </div>

@if (!empty($user_orders))
                            <div id="dataList" class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center bg-secondary">
                                            <th class="fw-bold">Offre</th>
                                            <th class="fw-bold">Prix unitaire</th>
                                            <th class="fw-bold">Quantité</th>
                                            <th class="fw-bold">Prix total</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
    @foreach ($items as $item)
                                        <tr>
                                            <td style="max-width: 16rem;">
                                                <img src="{{ count($item['product']['photos']) > 0 ? $item['product']['photos'][0]['file_url'] : asset('assets/img/undefined.png') }}" alt="{{ $item['product']['product_name'] }}" width="50" style="float: left; margin-right: 1rem;">
                                                <a href="{{ route('product.datas', ['id' => $item['product']['id']]) }}">
                                                    {{ $item['product']['product_name'] }}
                                                </a>
                                            </td>
                                            <td class="text-center">{{ $item['converted_price_at_that_time'] . ' ' . $logged_in_user['readable_currency'] }}</td>
                                            <td>
												<div class="d-flex flex-row">
													<input type="text" name="quantity" id="order-quantity-{{ $item['id'] }}" class="form-control text-center{{ $item['product']['quantity'] == 0 ? ' disabled' : '' }}" value="{{ $item['quantity'] }}" onchange="updateProductQuantity('update', {{ $item['id'] }}, this.value)" style="width: 80px;">
                                                    <div class="d-flex flex-column">
        @if ($item['product']['quantity'] > 0)
                                                        <a role="button" class="btn btn-secondary px-2 pt-1 pb-0" onclick="event.preventDefault(); updateProductQuantity('increment', {{ $item['id'] }});">
                                                            <i class="fa fa-angle-up"></i>
                                                        </a>
        @endif
		@if ($item['quantity'] > 1)
                                                        <a role="button" class="btn btn-secondary px-2 pt-1 pb-0" onclick="event.preventDefault(); updateProductQuantity('decrement', {{ $item['id'] }});">
                                                            <i class="fa fa-angle-down"></i>
                                                        </a>
		@endif
                                                    </div>
												</div>
                                            </td>
                                            <td class="text-center">{{ $item['readable_sub_total'] . ' ' . $logged_in_user['readable_currency'] }}</td>
                                            <td>
                                                <a role="button" class="btn btn-secondary px-2 pt-1 pb-0 rounded-circle text-primary" onclick="event.preventDefault(); performAction('delete', 'order', 'item-{{ $item['id'] }}')" title="Retirer du panier" style="width: 30px; height: 30px;">
                                                    <i class="bi bi-x-lg"></i>
                                                </a>
                                            </td>
                                        </tr>
    @endforeach
                                    </tbody>
                                </table>

								<table class="table table-bordered mt-3">
									<tbody>
										<tr>
											<td colspan="4" class="text-end">
                                                <strong>{{ 'TOTAL : ' . $logged_in_user['unpaid_cart_total'] . ' ' . $logged_in_user['readable_currency'] }}</strong>
                                            </td>
										</tr>
									</tbody>
								</table>

								<div class="mt-4 text-end">
                                    <button class="btn btn-lg adrm-btn-red rounded-pill" data-toggle="modal" data-target="#payModal">Effectuer le paiement</button>
                                </div>
                            </div>
@else
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <p class="mb-0"><i class="bi bi-cart3 fs-1"></i></p>
                            <p class="mb-0">La liste est vide</p>
                        </div>
@endif
                        </div>
