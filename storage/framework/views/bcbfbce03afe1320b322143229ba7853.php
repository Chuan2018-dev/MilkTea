<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #<?php echo e($order->order_number); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        .receipt {
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .border-top {
            border-top: 1px dashed #000;
        }
        .border-bottom {
            border-bottom: 1px dashed #000;
        }
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="text-center mb-3">
            <h4 class="mb-1">MILK TEA SHOP</h4>
            <p class="mb-0">123 Tea Street</p>
            <p class="mb-0">Milk City, MC 12345</p>
            <p class="mb-0">(555) 123-4567</p>
        </div>

        <div class="border-top border-bottom py-2 mb-3">
            <div class="d-flex justify-content-between">
                <span>Order #:</span>
                <span><?php echo e($order->order_number); ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Date:</span>
                <span><?php echo e($order->created_at->format('M d, Y h:i A')); ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Status:</span>
                <span><?php echo e($order->status_label); ?></span>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="mb-3">
            <p class="mb-1"><strong>Customer:</strong> <?php echo e($order->customer_name); ?></p>
            <p class="mb-1"><strong>Phone:</strong> <?php echo e($order->customer_phone); ?></p>
            <p class="mb-0"><strong>Address:</strong> <?php echo e($order->delivery_address); ?></p>
        </div>

        <div class="border-top border-bottom py-2 mb-3">
            <p class="mb-0 text-center"><strong>ORDER ITEMS</strong></p>
        </div>

        <!-- Items -->
        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mb-2">
            <div class="d-flex justify-content-between">
                <span><?php echo e($item->product_name); ?></span>
                <span>x<?php echo e($item->quantity); ?></span>
            </div>
            <small class="text-muted">
                <?php echo e($item->size_label); ?>, <?php echo e($item->sugar_level_label); ?>, <?php echo e($item->ice_level_label); ?>

            </small>
            <?php if(!empty($item->addons_list)): ?>
            <br>
            <small class="text-muted">
                + <?php echo e(collect($item->addons_list)->pluck('name')->implode(', ')); ?>

            </small>
            <?php endif; ?>
            <div class="text-end">
                <strong><?php echo e($item->formatted_subtotal); ?></strong>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <div class="border-top border-bottom py-2 mb-3">
            <div class="d-flex justify-content-between">
                <span>Subtotal:</span>
                <span><?php echo e($order->formatted_subtotal); ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Tax:</span>
                <span><?php echo e($order->formatted_tax); ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span><strong>TOTAL:</strong></span>
                <span><strong><?php echo e($order->formatted_total); ?></strong></span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <span>Payment Method:</span>
                <span><?php echo e(ucfirst($order->payment_method)); ?></span>
            </div>
            <div class="d-flex justify-content-between">
                <span>Payment Status:</span>
                <span><?php echo e(ucfirst($order->payment_status)); ?></span>
            </div>
        </div>

        <?php if($order->notes): ?>
        <div class="border-top pt-2 mb-3">
            <p class="mb-0"><strong>Notes:</strong> <?php echo e($order->notes); ?></p>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="text-center mt-4">
            <p class="mb-0">Thank you for your order!</p>
            <p class="mb-0">Please come again</p>
        </div>
    </div>

    <!-- Print Button -->
    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer me-2"></i>Print Receipt
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="bi bi-x-circle me-2"></i>Close
        </button>
    </div>

    <script>
        window.onload = function() {
            // Auto print when page loads
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\Kimi_Agent\milk-tea-shop\resources\views\admin\orders\print.blade.php ENDPATH**/ ?>