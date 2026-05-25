<aside class="booking-summary booking-summary-wrapper">
    <div class="summary-card booking-summary-sticky">
        <h3>Tóm tắt đặt phòng</h3>

        <div class="summary-group">
            <span>Chi nhánh</span>
            <strong>{{ $branch['name'] }}</strong>
        </div>

        <div class="summary-group">
            <span>Loại phòng</span>
            <strong id="summaryRoomName">Chưa chọn</strong>
        </div>

        <div class="summary-group">
            <span>Ngày lưu trú</span>
            <strong id="summaryDates">Chưa chọn</strong>
        </div>

        <div class="summary-group">
            <span>Số đêm</span>
            <strong id="summaryNights">0 đêm</strong>
        </div>

        <div class="summary-divider"></div>

        <div class="summary-section">
            <h4>Thú cưng</h4>
            <div id="summaryPetList">
                <p class="summary-empty">Chưa chọn thú cưng</p>
            </div>
        </div>

        <div class="summary-divider"></div>

        <div class="summary-section">
            <h4>Dịch vụ</h4>
            <div id="summaryServiceList">
                <p class="summary-empty">Chưa chọn dịch vụ</p>
            </div>
        </div>

        <div class="summary-divider"></div>

        <div class="summary-total-row">
            <span>Tạm tính phòng</span>
            <strong id="summaryRoomTotal">0đ</strong>
        </div>

        <div class="summary-total-row">
            <span>Tổng dịch vụ</span>
            <strong id="summaryServiceTotal">0đ</strong>
        </div>

        <div class="summary-divider"></div>

        <div class="summary-grand-total">
            <span>Tổng cộng</span>
            <strong id="summaryGrandTotal">0đ</strong>
        </div>

        <p class="summary-action-hint" id="summaryActionHint">
            Vui lòng chọn loại phòng, ngày lưu trú và thú cưng trước khi thanh toán.
        </p>

        <button
            type="submit"
            form="bookingForm"
            class="summary-pay-btn"
            id="bookingSubmitBtn"
            aria-describedby="summaryActionHint"
            disabled
        >
            Thanh toán
        </button>

        <button
            type="button"
            class="summary-hold-btn"
            id="bookingHoldBtn"
            disabled
            aria-disabled="true"
        >
            Giữ chỗ, thanh toán sau
        </button>
    </div>
</aside>
