<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<!-- <script src="../libraries/gsap/gsap.min.js"></script>
<script src="../libraries/gsap/ScrollTrigger.min.js"></script>
<script src="../libraries/just-validate/just-validate.production.min.js"></script>
<link rel="stylesheet" href="../libraries/bootstrap-icons/bootstrap-icons.min.css">
<link rel="stylesheet" href="../libraries/bootstrap/bootstrap.min.css">
<script src="../libraries/bootstrap/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="../libraries/sweetalert2/sweetalert2.min.css">
<script src="../libraries/sweetalert2/sweetalert2.all.min.js"></script> -->

<script src="../js/navigation.js"></script>
<script src="../js/reload.js"></script> 

<link id="favicon" rel="icon" href="../logo/frame_0001.png">
<script>
// List of icon frames
const frames = [
      "../logo/frame_0001.png",
      "../logo/frame_0002.png",
      "../logo/frame_0003.png",
      "../logo/frame_0004.png",
      "../logo/frame_0005.png",
      "../logo/frame_0006.png",
      "../logo/frame_0007.png",
      "../logo/frame_0008.png",
      "../logo/frame_0009.png",
      "../logo/frame_0010.png",
      "../logo/frame_0011.png",
      "../logo/frame_0012.png",
      "../logo/frame_0013.png",
      "../logo/frame_0014.png",
      "../logo/frame_0015.png",
      "../logo/frame_0016.png",
      "../logo/frame_0017.png",
      "../logo/frame_0018.png",
      "../logo/frame_0019.png",
      "../logo/frame_0020.png",
      "../logo/frame_0021.png",
      "../logo/frame_0022.png",
      "../logo/frame_0023.png",
      "../logo/frame_0024.png",
      "../logo/frame_0025.png",
      "../logo/frame_0026.png",
      "../logo/frame_0027.png",
      "../logo/frame_0028.png",
      "../logo/frame_0029.png",
      "../logo/frame_0030.png",
      "../logo/frame_0031.png",
      "../logo/frame_0032.png",
      "../logo/frame_0033.png",
      "../logo/frame_0034.png",
      "../logo/frame_0035.png",
      "../logo/frame_0036.png",
      "../logo/frame_0037.png",
      "../logo/frame_0038.png",
      "../logo/frame_0039.png",
      "../logo/frame_0040.png",
      "../logo/frame_0041.png",
      "../logo/frame_0042.png",
      "../logo/frame_0043.png",
      "../logo/frame_0044.png",
      "../logo/frame_0045.png",
      "../logo/frame_0046.png",
      "../logo/frame_0047.png",
      "../logo/frame_0048.png",
      "../logo/frame_0049.png",
      "../logo/frame_0050.png",
      "../logo/frame_0051.png",
      "../logo/frame_0052.png",
      "../logo/frame_0053.png",
      "../logo/frame_0054.png",
      "../logo/frame_0055.png",
      "../logo/frame_0056.png",
      "../logo/frame_0057.png",
      "../logo/frame_0058.png",
      "../logo/frame_0059.png",
      "../logo/frame_0060.png",
      "../logo/frame_0061.png",
      "../logo/frame_0062.png",
      "../logo/frame_0063.png",
      "../logo/frame_0064.png",
      "../logo/frame_0065.png",
      "../logo/frame_0066.png",
      "../logo/frame_0067.png",
      "../logo/frame_0068.png",
      "../logo/frame_0069.png",
      "../logo/frame_0070.png",
      "../logo/frame_0071.png",
      "../logo/frame_0072.png",
      "../logo/frame_0073.png",
      "../logo/frame_0074.png",
      "../logo/frame_0075.png",
      "../logo/frame_0076.png",
      "../logo/frame_0077.png",
      "../logo/frame_0078.png",
      "../logo/frame_0079.png",
      "../logo/frame_0080.png",
      "../logo/frame_0081.png",
      "../logo/frame_0082.png",
      "../logo/frame_0083.png",
      "../logo/frame_0084.png",
      "../logo/frame_0085.png",
      "../logo/frame_0086.png",
      "../logo/frame_0087.png",
      "../logo/frame_0088.png",
      "../logo/frame_0089.png",
      "../logo/frame_0090.png",
      "../logo/frame_0091.png",
      "../logo/frame_0092.png",
      "../logo/frame_0093.png",
      "../logo/frame_0094.png",
      "../logo/frame_0095.png",
      "../logo/frame_0096.png",
      "../logo/frame_0097.png",
      "../logo/frame_0098.png",
      "../logo/frame_0099.png",
      "../logo/frame_0100.png",
      "../logo/frame_0101.png",
      "../logo/frame_0102.png",
      "../logo/frame_0103.png",
      "../logo/frame_0104.png",
      "../logo/frame_0105.png",
      "../logo/frame_0106.png",
      "../logo/frame_0107.png",
      "../logo/frame_0108.png",
      "../logo/frame_0109.png",
      "../logo/frame_0110.png",
      "../logo/frame_0111.png",
      "../logo/frame_0112.png",
      "../logo/frame_0113.png",
      "../logo/frame_0114.png",
      "../logo/frame_0115.png",
      "../logo/frame_0116.png",
      "../logo/frame_0117.png",
      "../logo/frame_0118.png",
      "../logo/frame_0119.png",
      "../logo/frame_0120.png",
      "../logo/frame_0121.png",
      "../logo/frame_0122.png",
      "../logo/frame_0123.png",
      "../logo/frame_0124.png",
      "../logo/frame_0125.png",
      "../logo/frame_0126.png",
      "../logo/frame_0127.png",
      "../logo/frame_0128.png",
      "../logo/frame_0129.png",
      "../logo/frame_0130.png",
      "../logo/frame_0131.png",
      "../logo/frame_0132.png",
      "../logo/frame_0133.png",
      "../logo/frame_0134.png",
      "../logo/frame_0135.png",
      "../logo/frame_0136.png",
      "../logo/frame_0137.png",
      "../logo/frame_0138.png",
      "../logo/frame_0139.png",
      "../logo/frame_0140.png",
      "../logo/frame_0141.png",
      "../logo/frame_0142.png",
      "../logo/frame_0143.png",
      "../logo/frame_0144.png",
      "../logo/frame_0145.png"
];
let index = 0;
function animateFavicon() {
const link = document.getElementById("favicon") || document.createElement("link");
link.id = "favicon";
link.rel = "icon";
link.href = frames[index];
document.head.appendChild(link);
index = (index + 1) % frames.length;
}
// Change the favicon every 200ms
setInterval(animateFavicon, 40);
</script>
