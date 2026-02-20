# socialmedia_booster1

 <form method="POST">

                                <!-- Category -->
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <input type="text" id="orderCategory" name="order_category"
                                           class="form-control bg-light" readonly>
                                </div>

                                <!-- Service -->
                                <div class="mb-3">
                                    <label class="form-label">Service</label>
                                    <input type="text" id="orderName" name="order_name"
                                           class="form-control bg-light" readonly>
                                </div>

                                <input type="hidden" id="orderRate" name="orderRate">
                                <input type="hidden" id="orderService" name="service">

                                <!-- Quantity -->
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" id="quanity" name="quanity"
                                           class="form-control" required>
                                </div>

                                <!-- URL -->
                                <div class="mb-3">
                                    <label class="form-label">Target URL</label>
                                    <input type="url" name="order_url"
                                           class="form-control" required>
                                </div>

                                <!-- Total Price -->
                                <div class="mb-3">
                                    <label class="form-label">Total Price</label>
                                    <input type="text" id="totalPrice"
                                           class="form-control bg-light fw-bold" readonly>
                                </div>

                                <!-- Breakdown -->
                                <div class="alert alert-secondary small" id="priceBreakdown">
                                    Price breakdown will appear here.
                                </div>

                                <!-- Notes -->
                                <div class="mb-4">
                                    <label class="form-label">Additional Notes</label>
                                    <textarea name="message"
                                              class="form-control"
                                              rows="3"></textarea>
                                </div>

                                <!-- Submit -->
                                <div class="d-grid">
                                    <button type="submit" name="send_message"
                                            class="btn btn-primary btn-lg rounded-3">
                                        <i class="bi bi-send-fill me-2"></i>
                                        Submit Order
                                    </button>
                                </div>

                            </form>