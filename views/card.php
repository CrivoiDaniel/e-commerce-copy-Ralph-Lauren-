<?php
function card($src, $name, $price, $colorHex)
{
?>
    <div class="mx-[25px] flex flex-col border-2 rounded-lg border-2 ">
        <div class="m-[10px] cursor-pointer">
            <img src="<?= $src ?>" alt="" class="w-full h-full rounded-lg">
        </div>
        <div class="m-[10px] flex flex-col justify-between">
            <p class="text-black/60 text-[14px]"><?= $name; ?></p>
            <div class="flex justify-between mt-3">
                <p class="text-black text-[12px] font-semibold"><?= $price; ?></p>
                <span class="w-5 h-5 rounded-md border" style="background-color: <?= $colorHex ?>;"></span>
            </div>
        </div>
        <div class="flex justify-end m-3 gap-3">
            <div class=" bg-[#050A30]/80 hover:bg-[#050A30] w-1/3 rounded-md cursor-pointer">
                <p class="text-white text-[14px] text-center py-2"> View Details</p>
            </div>
            <div class=" bg-[#050A30] hover:bg-[#050A30]/80 w-1/3  rounded-md cursor-pointer">
                <p class="text-white text-[14px] text-center py-2">ADD TO BAG</p>
            </div>
        </div>

    </div>
<?php
}
