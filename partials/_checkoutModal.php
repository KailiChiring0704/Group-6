<!-- Checkout Modal with Payment Mode Selection -->
<?php
include 'partials/_dbconnect.php';
$totalPrice = 0;
$sql = "SELECT SUM(price * itemQuantity) AS totalPrice FROM `viewcart` vc JOIN `prod_sizes` ps ON vc.prodId = ps.prodId AND vc.size = ps.size WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $totalPrice = $row['totalPrice'];
}
?>

<style>
    .payment-modes-container {
        display: flex;
        justify-content: space-around;
        align-items: center;
    }

    .payment-mode-card {
        flex: 1;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: 0.25rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        margin: 0 5px;
        padding: 10px;
    }

    .payment-mode-card img {
        max-width: 80%;
        height: auto;
    }

    .payment-mode-card.active {
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(0, 0, 0, .2);
    }

    .payment-mode-card:not(.active):hover {
        transform: scale(1.03);
    }
</style>

<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Confirm Checkout</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="\partials\_manageCart.php" method="POST">
                    <label>Payment Mode:</label>
                    <div class="payment-modes-container">
                        <div class="payment-mode-card" data-value="0">
                            <img src="path_to_paymaya_icon" alt="PayMaya">
                            <p class="text-center mb-0">PayMaya</p>
                        </div>
                        <div class="payment-mode-card" data-value="1">
                            <img src="path_to_gcash_icon" alt="Gcash">
                            <p class="text-center mb-0">Gcash</p>
                        </div>
                        <div class="payment-mode-card" data-value="2">
                            <img src="path_to_cash_icon" alt="Cash">
                            <p class="text-center mb-0">Cash</p>
                        </div>
                    </div>
                    <input type="hidden" id="paymentMode" name="paymentMode" value="">
                    <input type="hidden" name="totalPrice" value="<?php echo htmlspecialchars($totalPrice); ?>">
            </div>
            <p class="text-center"><i class="fas fa-clock"></i> Claim at a store within 20 minutes. If not claimed, your order will auto cancel</p>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="submit" name="checkout" class="btn btn-primary">Confirm Order</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>

<script>
    document.querySelectorAll('.payment-mode-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.payment-mode-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('paymentMode').value = this.getAttribute('data-value');
        });
    });
</script>