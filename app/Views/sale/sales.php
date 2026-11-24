<div id="main" class="col-md-10 mt-5 float-md-right">
    <h2 class="mt-4">Sales</h2>
    <div class="row mt-3">
        <div class="col-lg">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url() ?>sales/add_sale">Add Sale</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sales</li>
                </ol>
            </nav>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th class="text-right">ID</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Discount</th>
                        <th class="text-right">Ground Total</th>
                        <th class="text-right">Date</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_sales as $v): ?>
                        <tr>
                            <td class="text-right"><?php echo esc($v->pk_sale_id) ?></td>
                            <td class="text-right"><?php echo number_format((float) $v->sale_subtotal, 2) ?></td>
                            <td class="text-right"><?php echo number_format((float) $v->sale_discount, 2) ?></td>
                            <td class="text-right"><?php echo number_format((float) $v->ground_total, 2) ?></td>
                            <td class="text-right"><?php echo esc($v->create_date) ?></td>
                            <td class="text-right">
                                <a href="<?php echo base_url() ?>sales/view_sale/<?php echo esc($v->pk_sale_id) ?>" class="btn btn-info btn-sm">View</a>
                                <a href="<?php echo base_url() ?>sales/add_sale/<?php echo esc($v->pk_sale_id) ?>" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
