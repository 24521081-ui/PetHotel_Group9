@extends('layouts.client')

@section('title', 'Dịch vụ Grooming')

@section('content')

@php
$randomGroomingImage = fn (): string => asset('assets/client/images/service/grooming/'.random_int(1, 12).'.jpg');


$randomServicePacketBasicImage = fn (): string =>
asset('assets/client/images/service/service-package/basic/'.random_int(1,
10).'.jpg');

$randomServicePacketSpaImage = fn (): string => asset('assets/client/images/service/service-package/spa/'.random_int(1,
10).'.jpg');

$randomServicePacketHighQualityImage = fn (): string =>
asset('assets/client/images/service/service-package/high-quality/'.random_int(1,
10).'.jpg');

$groomingHeroImage = $randomGroomingImage();
$groomingBasicImage = $randomServicePacketBasicImage();
$groomingSpaImage = $randomServicePacketSpaImage();
$groomingPremiumImage = $randomServicePacketHighQualityImage();

@endphp

<section class="grooming-hero" style="background-image: url('{{ $groomingHeroImage }}')">
  <div class="grooming-hero-overlay"></div>

  <div class="grooming-hero-content">
    <h1>Dịch vụ Grooming</h1>
    <p>Chăm sóc, làm đẹp và vệ sinh toàn diện cho thú cưng của bạn</p>

    <a href="{{ url('/booking') }}" class="grooming-primary-btn">
      Đặt lịch ngay
    </a>
  </div>
</section>

<section class="grooming-section">
  <div class="grooming-container">
    <h2 class="grooming-section-title">Dịch vụ nổi bật</h2>

    <div class="grooming-service-grid">

      <div class="grooming-service-card">
        <div class="grooming-service-icon">
          <i class="fa-solid fa-shower"></i>
        </div>
        <h3>Tắm cơ bản</h3>
        <p>Làm sạch lông, khử mùi và giúp thú cưng thoải mái hơn.</p>
        <strong>100.000đ</strong>
      </div>

      <div class="grooming-service-card">
        <div class="grooming-service-icon">
          <i class="fa-solid fa-scissors"></i>
        </div>
        <h3>Cắt tỉa lông</h3>
        <p>Tạo kiểu gọn gàng, phù hợp với từng giống chó mèo.</p>
        <strong>150.000đ</strong>
      </div>

      <div class="grooming-service-card">
        <div class="grooming-service-icon">
          <i class="fa-regular fa-hand"></i>
        </div>
        <h3>Cắt móng</h3>
        <p>Cắt móng an toàn, hạn chế trầy xước và khó chịu khi di chuyển.</p>
        <strong>70.000đ</strong>
      </div>

      <div class="grooming-service-card">
        <div class="grooming-service-icon">
          <i class="fa-solid fa-tooth"></i>
        </div>
        <h3>Vệ sinh răng miệng</h3>
        <p>Hỗ trợ làm sạch khoang miệng và giảm mùi hôi miệng.</p>
        <strong>120.000đ</strong>
      </div>

    </div>
  </div>
</section>

<section class="grooming-section grooming-package-section">
  <div class="grooming-container">
    <h2 class="grooming-section-title">Gói chăm sóc</h2>

    <div class="grooming-package-list">

      <div class="grooming-package-card">
        <div class="grooming-package-image">
          <img src="{{ $groomingBasicImage }}" alt="Gói cơ bản">
        </div>

        <div class="grooming-package-content">
          <div class="grooming-package-header">
            <div>
              <h3>Gói Cơ Bản</h3>
              <p>Phù hợp với nhu cầu vệ sinh định kỳ</p>
            </div>

            <div class="grooming-package-price">
              <strong>180.000đ</strong>
              <span>/lần</span>
            </div>
          </div>

          <ul class="grooming-feature-list">
            <li><i class="fa-solid fa-check"></i> Tắm làm sạch</li>
            <li><i class="fa-solid fa-check"></i> Sấy khô lông</li>
            <li><i class="fa-solid fa-check"></i> Chải lông cơ bản</li>
            <li><i class="fa-solid fa-check"></i> Vệ sinh tai nhẹ nhàng</li>
          </ul>

          <div class="grooming-note">
            <h4>Lưu ý chăm sóc</h4>
            <p>
              Gói này phù hợp cho thú cưng có tình trạng lông bình thường, không quá rối
              và không cần tạo kiểu phức tạp.
            </p>
          </div>

          <a href="{{ url('/booking') }}" class="grooming-package-btn">
            Chọn gói này
          </a>
        </div>
      </div>

      <div class="grooming-package-card reverse">
        <div class="grooming-package-image">
          <img src="{{ $groomingSpaImage }}" alt="Gói Spa">
        </div>

        <div class="grooming-package-content">
          <div class="grooming-package-header">
            <div>
              <h3>Gói Spa</h3>
              <p>Chăm sóc thư giãn và làm sạch chuyên sâu</p>
            </div>

            <div class="grooming-package-price">
              <strong>300.000đ</strong>
              <span>/lần</span>
            </div>
          </div>

          <ul class="grooming-feature-list">
            <li><i class="fa-solid fa-check"></i> Tắm bằng sữa tắm chuyên dụng</li>
            <li><i class="fa-solid fa-check"></i> Massage thư giãn</li>
            <li><i class="fa-solid fa-check"></i> Cắt móng</li>
            <li><i class="fa-solid fa-check"></i> Vệ sinh tai và bàn chân</li>
          </ul>

          <div class="grooming-note">
            <h4>Lưu ý chăm sóc</h4>
            <p>
              Nhân viên sẽ kiểm tra tình trạng da, lông và biểu hiện của thú cưng trước khi
              thực hiện để đảm bảo bé không bị kích ứng hoặc căng thẳng.
            </p>
          </div>

          <a href="{{ url('/booking') }}" class="grooming-package-btn">
            Chọn gói này
          </a>
        </div>
      </div>

      <div class="grooming-package-card">
        <div class="grooming-package-image">
          <img src="{{ $groomingPremiumImage }}" alt="Gói cao cấp">
        </div>

        <div class="grooming-package-content">
          <div class="grooming-package-header">
            <div>
              <h3>Gói Cao Cấp</h3>
              <p>Làm đẹp toàn diện cho thú cưng</p>
            </div>

            <div class="grooming-package-price">
              <strong>450.000đ</strong>
              <span>/lần</span>
            </div>
          </div>

          <ul class="grooming-feature-list">
            <li><i class="fa-solid fa-check"></i> Tắm và dưỡng lông</li>
            <li><i class="fa-solid fa-check"></i> Cắt tỉa tạo kiểu</li>
            <li><i class="fa-solid fa-check"></i> Vệ sinh răng miệng</li>
            <li><i class="fa-solid fa-check"></i> Chăm sóc da, lông và móng</li>
          </ul>

          <div class="grooming-note">
            <h4>Lưu ý chăm sóc</h4>
            <p>
              Gói cao cấp phù hợp với thú cưng cần làm đẹp kỹ hơn, có nhu cầu tạo kiểu
              hoặc cần chăm sóc lông định kỳ để giữ vẻ ngoài sạch sẽ, gọn gàng.
            </p>
          </div>

          <a href="{{ url('/booking') }}" class="grooming-package-btn">
            Chọn gói này
          </a>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="grooming-section">
  <div class="grooming-container">
    <h2 class="grooming-section-title">Quy trình Grooming</h2>

    <div class="grooming-process-grid">
      <div class="grooming-step">
        <span>1</span>
        <h3>Tiếp nhận thú cưng</h3>
        <p>Kiểm tra tình trạng da, lông và ghi chú yêu cầu của chủ nuôi.</p>
      </div>

      <div class="grooming-step">
        <span>2</span>
        <h3>Tắm & làm sạch</h3>
        <p>Sử dụng sản phẩm phù hợp với từng loại lông và làn da.</p>
      </div>

      <div class="grooming-step">
        <span>3</span>
        <h3>Sấy & chải lông</h3>
        <p>Làm khô kỹ, chải gỡ rối và giữ lông mềm mượt.</p>
      </div>

      <div class="grooming-step">
        <span>4</span>
        <h3>Cắt tỉa</h3>
        <p>Tạo kiểu gọn gàng theo nhu cầu và đặc điểm từng bé.</p>
      </div>

      <div class="grooming-step">
        <span>5</span>
        <h3>Bàn giao</h3>
        <p>Kiểm tra lại, ghi chú tình trạng và bàn giao thú cưng cho khách.</p>
      </div>
    </div>

    <div class="grooming-care-box">
      <h3>Ghi chú an toàn khi Grooming</h3>

      <div class="grooming-care-grid">
        <div class="grooming-care-item">
          <h4>Kiểm tra trước dịch vụ</h4>
          <p>
            Trước khi thực hiện grooming, nhân viên sẽ kiểm tra sơ bộ da, lông,
            móng và biểu hiện của thú cưng. Nếu phát hiện vết thương, kích ứng
            hoặc biểu hiện bất thường, nhân viên sẽ thông báo cho chủ nuôi trước khi xử lý.
          </p>
        </div>

        <div class="grooming-care-item">
          <h4>Xử lý khi thú cưng căng thẳng</h4>
          <p>
            Nếu thú cưng quá sợ hãi, phản ứng mạnh hoặc không hợp tác,
            quy trình có thể được tạm dừng để bé nghỉ ngơi. Pet Hotel ưu tiên
            sự an toàn và cảm giác thoải mái của thú cưng trong suốt quá trình chăm sóc.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection