<!DOCTYPE html>
<html>
<head>
    <title>Create Supply Order</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Create Supply Order</h1>
        <form action="{{ route('supply_orders.store') }}" method="POST" id="supplyOrderForm">
            @csrf
            <div class="form-group">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control" required>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="order_date">Order Date</label>
                <input type="date" name="order_date" id="order_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="discount_type">Discount Type</label>
                <select name="discount_type" id="discount_type" class="form-control" required>
                    <option value="per_item">Per Item</option>
                    <option value="total">Total Invoice</option>
                </select>
            </div>

            <div class="form-group" id="total_discount_group" style="display: none;">
                <label for="discount_percentage">Discount Percentage (Total)</label>
                <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" step="0.01" min="0">
            </div>

            <h3>Items</h3>
            <table class="table" id="items_table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Company Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Discount % (Per Item)</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="text" name="items[0][name]" class="form-control" required></td>
                        <td><input type="text" name="items[0][company_name]" class="form-control" required></td>
                        <td><input type="number" name="items[0][quantity]" class="form-control quantity" required></td>
                        <td><input type="number" name="items[0][unit_price]" class="form-control unit_price" step="0.01" required></td>
                        <td><input type="number" name="items[0][discount_percentage]" class="form-control discount_percentage" step="0.01" value="0"></td>
                        <td><input type="text" class="form-control subtotal" readonly></td>
                        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="add_item" class="btn btn-primary">Add Item</button>

            <div class="form-group">
                <label>Total Quantity</label>
                <input type="number" name="total_quantity" id="total_quantity" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Total Cost Before Discount</label>
                <input type="text" name="total_cost_before_discount" id="total_cost_before_discount" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Total Discount Amount</label>
                <input type="text" name="discount_amount" id="discount_amount" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Total Cost After Discount</label>
                <input type="text" name="total_cost_after_discount" id="total_cost_after_discount" class="form-control" readonly>
            </div>

            <div class="form-group">
                <label for="note">Note</label>
                <textarea name="note" id="note" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Save Supply Order</button>
        </form>
    </div>

    <script>
        let rowIndex = 1;

        $('#add_item').click(function() {
            let newRow = `
                <tr>
                    <td><input type="text" name="items[${rowIndex}][name]" class="form-control" required></td>
                    <td><input type="text" name="items[${rowIndex}][company_name]" class="form-control" required></td>
                    <td><input type="number" name="items[${rowIndex}][quantity]" class="form-control quantity" required></td>
                    <td><input type="number" name="items[${rowIndex}][unit_price]" class="form-control unit_price" step="0.01" required></td>
                    <td><input type="number" name="items[${rowIndex}][discount_percentage]" class="form-control discount_percentage" step="0.01" value="0"></td>
                    <td><input type="text" class="form-control subtotal" readonly></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>`;
            $('#items_table tbody').append(newRow);
            rowIndex++;
            updateTotals();
        });

        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            updateTotals();
        });

        $('#discount_type').change(function() {
            if ($(this).val() === 'total') {
                $('#total_discount_group').show();
                $('.discount_percentage').prop('disabled', true).val(0);
            } else {
                $('#total_discount_group').hide();
                $('.discount_percentage').prop('disabled', false);
            }
            updateTotals();
        });

        $(document).on('input', '.quantity, .unit_price, .discount_percentage, #discount_percentage', updateTotals);

        function updateTotals() {
            let totalQuantity = 0;
            let totalCostBeforeDiscount = 0;
            let totalDiscountAmount = 0;

            $('#items_table tbody tr').each(function() {
                let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                let unitPrice = parseFloat($(this).find('.unit_price').val()) || 0;
                let discountPercentage = parseFloat($(this).find('.discount_percentage').val()) || 0;
                let subtotal = quantity * unitPrice;
                let discountAmount = $('#discount_type').val() === 'per_item' ? (subtotal * discountPercentage / 100) : 0;

                $(this).find('.subtotal').val(subtotal.toFixed(2));
                totalQuantity += quantity;
                totalCostBeforeDiscount += subtotal;
                totalDiscountAmount += discountAmount;
            });

            if ($('#discount_type').val() === 'total') {
                let discountPercentage = parseFloat($('#discount_percentage').val()) || 0;
                totalDiscountAmount = totalCostBeforeDiscount * discountPercentage / 100;
            }

            $('#total_quantity').val(totalQuantity);
            $('#total_cost_before_discount').val(totalCostBeforeDiscount.toFixed(2));
            $('#discount_amount').val(totalDiscountAmount.toFixed(2));
            $('#total_cost_after_discount').val((totalCostBeforeDiscount - totalDiscountAmount).toFixed(2));
        }
    </script>
</body>
</html>
