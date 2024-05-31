<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="\partials\_manageCart.php" method="POST">
                    <input type="hidden" name="itemId" id="addToCartItemId">
                    <div class="form-group">
                        <label for="size">Size</label>
                        <div id="sizes" class="btn-group btn-group-toggle" data-toggle="buttons">
                            <!-- Size options will be dynamically populated here -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantityInput" name="quantity" value="1" min="1">
                    </div>
                    <div class="form-group">
                        <label for="totalPrice">Total Price</label>
                        <input type="text" class="form-control" id="totalPriceDisplay" name="totalPriceDisplay" value="PHP 0.00" readonly>
                        <input type="hidden" id="totalPrice" name="totalPrice"> <!-- Hidden field that holds the numeric value -->
                    </div>

                    <button type="submit" name="addToCart" class="btn btn-primary">Add item to cart</button>
                </form>
            </div>
        </div>
    </div>
</div>