<?php
include 'includes/header.php';
?>
<div class="container mt-5">
    <!-- Notification -->
    <div id="toastMessage" class="alert d-none fixed-top mt-3 mx-auto shadow" style="max-width: 400px; z-index: 9999;"></div>

    <div class="row" id="productDetail">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status"></div>
        </div>
    </div>
</div>

<script>
const formatVND = (price) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);

function showToast(msg, type = 'success') {
    const toast = document.getElementById('toastMessage');
    toast.className = `alert alert-${type} fixed-top mt-3 mx-auto shadow`;
    toast.textContent = msg;
    toast.classList.remove('d-none');
    setTimeout(() => toast.classList.add('d-none'), 3000);
}

async function loadProductDetail() {
    const container = document.getElementById('productDetail');
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    if (!productId) {
        window.location.href = 'products.php';
        return;
    }

    try {
        const res = await fetchAPI(`/product/detail.php?id=${productId}`);
        const p = res.data;
        
        const parts = p.image.split('/');
        const imgName = parts[parts.length - 1];

        // Format description with <br> instead of \n
        const descHTML = p.description.replace(/\n/g, '<br>');

        container.innerHTML = `
            <div class="col-md-5">
                <img
                    src="uploads/${imgName}"
                    class="img-fluid rounded shadow"
                    alt="${p.name}"
                    onerror="this.src='https://placehold.co/400x400/eeeeee/999999?text=No+Image';">
            </div>
            <div class="col-md-7">
                <h2> ${p.name} </h2>
                <h3 class="text-danger my-3">
                    ${formatVND(p.price)}
                </h3>
                <p>
                    <strong>Mô tả sản phẩm:</strong>
                </p>
                <p> ${descHTML} </p>
                
                <div class="d-flex mt-4 gap-3 align-items-center">
                    <div class="input-group" style="max-width:150px;">
                        <span class="input-group-text">SL</span>
                        <input type="number" id="qty" class="form-control text-center" value="1" min="1">
                    </div>
                    <button onclick="addToCart(${p.id})" class="btn btn-success btn-lg"> 🛒 Thêm vào giỏ hàng </button>
                </div>
            </div>
        `;
    } catch (err) {
        container.innerHTML = `<div class="col-12"><div class="alert alert-danger">Lỗi tải chi tiết: ${err.message}</div></div>`;
    }
}

async function addToCart(productId) {
    const qty = document.getElementById('qty').value;
    try {
        const res = await fetchAPI('/cart/add.php', {
            method: 'POST',
            body: { product_id: productId, quantity: qty }
        });
        showToast(res.message, 'success');
    } catch (err) {
        showToast(err.message, 'danger');
    }
}

document.addEventListener('DOMContentLoaded', loadProductDetail);
</script>

<?php include 'includes/footer.php'; ?>