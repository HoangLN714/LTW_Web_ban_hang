# 🛒 Website Bán Hàng Công Nghệ (PHP & MySQL)

Website bán hàng trực tuyến được xây dựng bằng ngôn ngữ **PHP thuần**, kết hợp với cơ sở dữ liệu **MySQL** và giao diện **Bootstrap 5**. Dự án hỗ trợ đầy đủ các tính năng của một trang web thương mại điện tử cơ bản, chia làm 2 phân hệ rõ ràng: Khách hàng (Frontend) và Quản trị viên (Backend Admin).

---

## 🚀 Tính Năng Chính

### 👥 Phân hệ Khách hàng (Frontend)
* **Trang chủ:** Hiển thị Banner quảng cáo banner và danh sách sản phẩm nổi bật.
* **Danh sách sản phẩm:** Hỗ trợ tìm kiếm sản phẩm theo tên, bộ lọc layout lưới (Grid) không vỡ khung.
* **Giỏ hàng (Cart):** Thêm sản phẩm vào giỏ hàng, cập nhật số lượng, tự động tính tổng tiền.
* **Tài khoản:** Đăng ký thành viên, Đăng nhập hệ thống, Xem lịch sử đơn hàng cá nhân.

### 🛡️ Phân hệ Quản trị (Backend Admin Panel)
* **Dashboard:** Thống kê tổng quan hệ thống.
* **Quản lý sản phẩm:** Xem danh sách, Thêm mới (hỗ trợ upload ảnh), Sửa thông tin (giữ/đổi ảnh), Xóa sản phẩm.
* **Quản lý danh mục:** Thêm, sửa, xóa danh mục sản phẩm.
* **Quản lý đơn hàng:** Tiếp nhận và theo dõi các đơn đặt hàng từ khách.
* **Quản lý người dùng:** Xem và quản lý danh sách thành viên.

---

## 📂 Cấu Trúc Thư Mục Dự Án

Dự án được tổ chức mô-đun hóa gọn gàng theo cấu trúc thực tế:

```text
web_ban_hang/
├── admin/                  # Phân hệ quản trị viên (Admin Panel)
│   ├── category.php        # Quản lý danh mục
│   ├── footer.php          # Giao diện chân trang Admin
│   ├── header.php          # Giao diện đầu trang & check quyền Admin
│   ├── index.php           # Trang chủ Dashboard Admin
│   ├── order.php           # Quản lý đơn hàng
│   ├── product-add.php     # Xử lý thêm sản phẩm mới
│   ├── product-edit.php    # Xử lý chỉnh sửa sản phẩm
│   ├── product-list.php    # Hiển thị danh sách sản phẩm hệ thống
│   └── user_list.php       # Quản lý người dùng
├── config/                 # Cấu hình hệ thống
│   └── db.php              # Kết nối Cơ sở dữ liệu MySQL
├── uploads/                # Thư mục lưu trữ hình ảnh sản phẩm upload lên
├── cart.php                # Xử lý giỏ hàng
├── checkout.php            # Xử lý đặt hàng & thanh toán
├── footer.php              # Giao diện chân trang người dùng
├── header.php              # Giao diện đầu trang & thanh điều hướng (Navbar)
├── index.php               # Trang chủ phía người dùng
├── login.php               # Trang đăng nhập
├── logout.php              # Xử lý đăng xuất
├── products.php            # Toàn bộ danh sách sản phẩm & Tìm kiếm
├── register.php            # Trang đăng ký tài khoản
└── README.md               # File tài liệu hướng dẫn này
