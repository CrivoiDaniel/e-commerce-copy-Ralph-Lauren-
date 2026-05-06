let selectedSize = null;
let selectedColor = null;
let variants = [];

document.addEventListener('DOMContentLoaded', function () {

    const variantsData = document.getElementById('variantsData');
    if (variantsData) {
        variants = JSON.parse(variantsData.textContent);
    }
});

function selectSize(size, el) {
    selectedSize = size;
    document.getElementById('selectedSize').value = size;

    document.querySelectorAll('.size-btn').forEach(btn => {
        btn.classList.remove('border-black', 'bg-gray-100', 'border-2');
        btn.classList.add('border-gray-300', 'border-2');
    });
    el.classList.remove('border-gray-300');
    el.classList.add('border-black', 'bg-gray-100');

    updateProductInfo();
    checkReady();
}

function selectColor(color, el) {
    selectedColor = color;
    document.getElementById('selectedColor').value = color;

    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.classList.remove('border-black');
        btn.classList.add('border-gray-300');
    });
    el.classList.remove('border-gray-300');
    el.classList.add('border-black');

    updateProductInfo();
    checkReady();
}

function updateProductInfo() {
    if (!selectedSize || !selectedColor) {
        return;
    }

    const variant = variants.find(v =>
        v.Size === selectedSize && v.Color === selectedColor
    );

    if (variant) {

        const priceElement = document.getElementById('price');
        if (priceElement) {
            priceElement.textContent = `€${parseFloat(variant.Price).toFixed(2)}`;
        }

        const mainImage = document.getElementById('mainImage');
        if (mainImage && variant.Image) {
            mainImage.src = `../uploads/products/${variant.Image}`;
        }

        const stockElement = document.getElementById('stock');
        if (stockElement) {
            if (variant.Stock > 0) {
                stockElement.textContent = `In stock: ${variant.Stock} items`;
                stockElement.className = 'mt-2 text-sm text-green-600 font-semibold';
            } else {
                stockElement.textContent = 'Out of stock';
                stockElement.className = 'mt-2 text-sm text-red-600 font-semibold';
            }
        }
    }
}

function checkReady() {
    const btn = document.getElementById('addToBagBtn');

    if (!selectedSize || !selectedColor) {
        btn.disabled = true;
        btn.classList.add('bg-gray-400', 'cursor-not-allowed');
        btn.classList.remove('bg-[#050A30]', 'hover:bg-[#050A30]/90');
        return;
    }
    const variant = variants.find(v =>
        v.Size === selectedSize && v.Color === selectedColor
    );

    if (variant && variant.Stock > 0) {
        btn.disabled = false;
        btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
        btn.classList.add('bg-[#050A30]', 'hover:bg-[#050A30]/90');
    } else {
        btn.disabled = true;
        btn.classList.add('bg-gray-400', 'cursor-not-allowed');
        btn.classList.remove('bg-[#050A30]', 'hover:bg-[#050A30]/90');
    }
}