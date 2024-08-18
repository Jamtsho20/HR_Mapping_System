<td class="text-center">
                                                                <a href="#" class="edit-detail-btn btn-sm btn btn-rounded btn-outline-success" 
                                                                data-id="{{ $detail->id }}"
                                                                data-pay-from="{{ $detail->pay_from }}"
                                                                data-pay-to="{{ $detail->pay_to }}"
                                                                data-amount="{{ $detail->amount }}"
                                                                data-created-at="{{ $detail->created_at->format('Y-m-d') }}"
                                                                data-updated-at="{{ $detail->updated_at->format('Y-m-d') }}">
                                                                <i class="fa fa-edit"></i> Edit
                                                                </a>
                                                                <a href="#" class="delete-btn btn btn-sm btn-rounded btn-outline-danger"
                                                                    data-id="{{ $detail->id }}"
                                                                    data-pay-from="{{ $detail->pay_from }}"
                                                                    data-pay-to="{{ $detail->pay_to }}"
                                                                    data-amount="{{ $detail->amount }}"
                                                                    data-created-at="{{ $detail->created_at->format('Y-m-d') }}"
                                                                    data-updated-at="{{ $detail->updated_at->format('Y-m-d') }}">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </a>
                                                            </td>