<div id="main" class="col-md-10 mt-5 float-md-right">
    <h2 class="mt-4">Sale #<?php echo esc($sale_info->pk_sale_id) ?></h2>
    <div class="row mt-3">
        <div class="col-lg">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>sales">Sales</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sale Details</li>
                </ol>
            </nav>
            <dl class="row">
                <dt class="col-sm-3">Date</dt>
                <dd class="col-sm-9"><?php echo esc($sale_info->create_date) ?></dd>
                <dt class="col-sm-3">Subtotal</dt>
                <dd class="col-sm-9"><?php echo number_format((float) $sale_info->sale_subtotal, 2) ?></dd>
                <dt class="col-sm-3">Discount</dt>
                <dd class="col-sm-9"><?php echo number_format((float) $sale_info->sale_discount, 2) ?></dd>
                <dt class="col-sm-3">Ground Total</dt>
                <dd class="col-sm-9"><?php echo number_format((float) $sale_info->ground_total, 2) ?></dd>
            </dl>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th class="text-right">Quantity</th>
                        <th class="text-right">Weight</th>
                        <th class="text-right">Box Unit</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sale_details as $detail): ?>
                        <tr>
                            <td><?php echo esc($detail->product_name) ?></td>
                            <td class="text-right"><?php echo esc($detail->product_quantity) ?></td>
                            <td class="text-right"><?php echo esc($detail->product_weight) ?></td>
                            <td class="text-right"><?php echo esc($detail->box_unit) ?></td>
                            <td class="text-right"><?php echo number_format((float) $detail->sale_price, 2) ?></td>
                            <td class="text-right"><?php echo number_format((float) $detail->product_quantity * (float) $detail->sale_price, 2) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <a href="<?php echo base_url() ?>sales/add_sale/<?php echo esc($sale_info->pk_sale_id) ?>" class="btn btn-primary btn-sm">Edit</a>
            <a href="<?php echo base_url() ?>sales" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>
</div>
