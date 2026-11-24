<?php
$details = $sale_details ?? [];
if ($details === []) {
    $details = [null];
}
?>
<div id="main" class="col-md-10 mt-5 float-md-right">
    <h2 class="mt-4"><?php echo esc($title) ?></h2>
    <div class="row mt-3">
        <div class="col-lg">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>sales">Sales</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo esc($title) ?></li>
                </ol>
            </nav>
            <form id="processOperation">
                <?= csrf_field() ?>
                <div class="row">
                    <div class="col-md-12">
                        <div id="result"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Discount</label>
                            <input type="number" step="0.01" min="0" name="discount" value="<?php echo isset($sale_info) ? esc($sale_info->sale_discount) : '0' ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="width: 80px">Box</th>
                            <th style="width: 80px">Qty</th>
                            <th style="width: 120px">Sale Price</th>
                            <th style="width: 50px"></th>
                        </tr>
                    </thead>
                    <tbody id="itemRows">
                        <?php foreach ($details as $index => $detail): ?>
                            <?php
                            $selectedProduct = $detail === null ? 0 : (int) $detail->fk_product_id;
                            $boxUnit        = $detail === null ? 0 : (int) $detail->box_unit;
                            $quantity       = $detail === null ? 0 : (int) $detail->product_quantity;
                            $boxes          = $boxUnit > 0 ? intdiv($quantity, $boxUnit) : 0;
                            $looseQty       = $boxUnit > 0 ? $quantity % $boxUnit : $quantity;
                            ?>
                            <tr>
                                <td>
                                    <select required name="items[<?php echo $index ?>][product_id]" class="form-control">
                                        <option value="">Select One</option>
                                        <?php foreach ($all_products as $product): ?>
                                            <option value="<?php echo esc($product->pk_product_id) ?>" <?php echo $product->pk_product_id == $selectedProduct ? 'selected' : '' ?>>
                                                <?php echo esc($product->product_name) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                                <td><input type="number" min="0" name="items[<?php echo $index ?>][box]" value="<?php echo $boxes ?>" class="form-control"></td>
                                <td><input type="number" min="0" name="items[<?php echo $index ?>][qty]" value="<?php echo $looseQty ?>" class="form-control"></td>
                                <td><input type="number" step="0.01" min="0" name="items[<?php echo $index ?>][sale_price]" value="<?php echo $detail === null ? '' : esc($detail->sale_price) ?>" class="form-control"></td>
                                <td><button type="button" class="btn btn-danger btn-sm remove-item">&times;</button></td>
                            </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
                <button type="button" id="addItem" class="btn btn-secondary btn-sm mb-3">Add Item</button>
                <button type="submit" id="submitProcess" class="btn btn-primary"><?php echo esc($title) ?></button>
            </form>
        </div>
    </div>
</div>
<script>
    jQ.push(function () {
        var rowIndex = <?php echo count($details) ?>;

        $('#addItem').click(function () {
            var row = $('#itemRows tr').first().clone();
            row.find('input, select').each(function () {
                this.name = this.name.replace('[0]', '[' + rowIndex + ']');
                if (this.tagName === 'SELECT') {
                    this.selectedIndex = 0;
                } else {
                    this.value = '';
                }
            });
            $('#itemRows').append(row);
            rowIndex++;
        });

        $(document).on('click', '.remove-item', function () {
            if ($('#itemRows tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });

        $('#submitProcess').click(function (e) {
            e.preventDefault();
            $('#result').html('');
            $('#submitProcess').attr('disabled', true).html('Processing..');

            $.ajax({
                type: 'POST',
                url: "<?php echo base_url() ?>sales/save_sale/<?php echo $edit ? $edit : '' ?>",
                data: $('#processOperation').serialize(),
                success: function (data) {
                    $('#submitProcess').attr('disabled', false).html('<?php echo esc($title) ?>');
                    if ($.trim(data) === 'success') {
                        $('#result').html("<div class='alert alert-success' role='alert'>Success</div>");
                    } else {
                        $('#result').html("<div class='alert alert-danger' role='alert'>Invalid</div>");
                    }
                }
            });
        });
    });
</script>
