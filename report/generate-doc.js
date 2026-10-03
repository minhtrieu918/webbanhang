const fs = require('fs');
const path = require('path');
const { execFileSync } = require('child_process');
const {
  Document,
  Packer,
  Paragraph,
  TextRun,
  HeadingLevel,
  AlignmentType,
  ImageRun,
} = require('docx');

const root = process.cwd();
const assets = path.join(root, 'report', 'assets');
const codeAssets = path.join(root, 'report', 'code-assets');
fs.mkdirSync(codeAssets, { recursive: true });

function escapeHtml(value) {
  return value.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function captureCodeScreenshot(codeFile, title, outputName) {
  const source = fs.readFileSync(path.join(root, codeFile), 'utf8');
  const htmlPath = path.join(codeAssets, `${outputName}.html`);
  const imagePath = path.join(codeAssets, `${outputName}.png`);
  const html = `<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <style>
    body { margin: 0; padding: 28px; background: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
    .window { width: 1500px; margin: 0 auto; overflow: hidden; border: 1px solid #cbd5e1; border-radius: 10px; background: #fff; box-shadow: 0 8px 24px rgba(15, 23, 42, .12); }
    .title { padding: 16px 22px; color: #fff; background: #1f2937; font-size: 24px; font-weight: 700; }
    pre { margin: 0; padding: 22px; color: #111827; background: #fff; font: 17px/1.55 Menlo, Monaco, Consolas, monospace; white-space: pre-wrap; }
  </style>
</head>
<body><div class="window"><div class="title">${title}</div><pre>${escapeHtml(source)}</pre></div></body>
</html>`;
  fs.writeFileSync(htmlPath, html);
  execFileSync('npx', ['playwright', 'screenshot', '--device=Desktop Chrome', `file://${htmlPath}`, imagePath], { stdio: 'inherit' });
  return imagePath;
}

const functions = [
  {
    title: '1. Quản lý và hiển thị sản phẩm',
    interfaceImage: '01-product.png',
    interfaceCaption: 'Ảnh giao diện: trang danh sách sản phẩm.',
    codeFile: 'app/views/product/list.php',
    codeImage: captureCodeScreenshot('app/views/product/list.php', 'list.php - Hiển thị danh sách sản phẩm', '01-product-list-view'),
    codeCaption: 'Ảnh code: view list.php chịu trách nhiệm hiển thị sản phẩm, giá, hình ảnh và nút thao tác.',
    description: 'Chức năng này cung cấp màn hình trung tâm để hiển thị các sản phẩm được lấy từ cơ sở dữ liệu. Người dùng có thể xem thông tin, chuyển tới trang sửa, xóa sản phẩm hoặc thêm sản phẩm vào giỏ hàng.',
  },
  {
    title: '2. Bổ sung sản phẩm mới',
    interfaceImage: '02-add-product.png',
    interfaceCaption: 'Ảnh giao diện: biểu mẫu bổ sung sản phẩm.',
    codeFile: 'app/controllers/ProductController.php',
    codeImage: captureCodeScreenshot('app/controllers/ProductController.php', 'ProductController.php - Thêm và xử lý sản phẩm', '02-product-controller'),
    codeCaption: 'Ảnh code: ProductController.php tiếp nhận dữ liệu biểu mẫu, xử lý upload ảnh và gọi model để lưu sản phẩm.',
    description: 'Chức năng bổ sung sản phẩm cho phép người quản trị nhập tên, mô tả, giá bán, danh mục và hình ảnh. Dữ liệu được kiểm tra trước khi lưu nhằm bảo đảm tính hợp lệ.',
  },
  {
    title: '3. Quản lý danh mục sản phẩm',
    interfaceImage: '03-category.png',
    interfaceCaption: 'Ảnh giao diện: danh sách và thao tác quản lý danh mục.',
    codeFile: 'app/controllers/CategoryController.php',
    codeImage: captureCodeScreenshot('app/controllers/CategoryController.php', 'CategoryController.php - Quản lý danh mục', '03-category-controller'),
    codeCaption: 'Ảnh code: CategoryController.php xử lý hiển thị, thêm, sửa, xóa và kiểm tra danh mục.',
    description: 'Phân hệ danh mục giúp tổ chức sản phẩm theo từng nhóm. Người quản trị có thể xem số lượng sản phẩm, bổ sung danh mục mới, cập nhật hoặc xóa dữ liệu theo quy tắc của hệ thống.',
  },
  {
    title: '4. Quản lý giỏ hàng',
    interfaceImage: '04-cart.png',
    interfaceCaption: 'Ảnh giao diện: giỏ hàng và tổng tiền đơn hàng.',
    codeFile: 'app/views/product/cart.php',
    codeImage: captureCodeScreenshot('app/views/product/cart.php', 'cart.php - Hiển thị và cập nhật giỏ hàng', '04-cart-view'),
    codeCaption: 'Ảnh code: view cart.php hiển thị sản phẩm, số lượng, thành tiền và các nút cập nhật hoặc xóa.',
    description: 'Giỏ hàng lưu lựa chọn của người dùng trong session. Màn hình cho phép điều chỉnh số lượng, xóa sản phẩm, tính tổng tiền và chuyển sang bước đặt hàng.',
  },
  {
    title: '5. Đặt hàng và thanh toán',
    interfaceImage: '05-checkout.png',
    interfaceCaption: 'Ảnh giao diện: biểu mẫu nhập thông tin nhận hàng và tổng đơn hàng.',
    codeFile: 'app/models/OrderModel.php',
    codeImage: captureCodeScreenshot('app/models/OrderModel.php', 'OrderModel.php - Tạo đơn hàng và chi tiết đơn hàng', '05-order-model'),
    codeCaption: 'Ảnh code: OrderModel.php tạo giao dịch, lưu đơn hàng và lưu các sản phẩm thuộc đơn hàng.',
    description: 'Quy trình đặt hàng kiểm tra thông tin người mua, tính tổng tiền và ghi dữ liệu vào orders cùng order_details. Giao dịch cơ sở dữ liệu được sử dụng để hạn chế tình trạng lưu thiếu dữ liệu.',
  },
];

function imageParagraph(imagePath, width = 700, height = 420) {
  return new Paragraph({
    children: [new ImageRun({ data: fs.readFileSync(imagePath), transformation: { width, height } })],
  });
}

const children = [
  new Paragraph({
    alignment: AlignmentType.CENTER,
    children: [new TextRun({ text: 'BÁO CÁO CÁC CHỨC NĂNG WEBSITE BÁN HÀNG', bold: true, size: 32 })],
  }),
  new Paragraph({ text: 'Báo cáo trình bày các chức năng đã xây dựng trong website bán hàng trực tuyến sử dụng PHP, MySQL và mô hình kiến trúc MVC. Đối với mỗi chức năng, báo cáo đặt ảnh giao diện cạnh phần ảnh chụp mã nguồn tương ứng nhằm làm rõ mối liên hệ giữa phần hiển thị và phần xử lý.' }),
  new Paragraph({ text: 'Các ảnh mã nguồn được tạo trực tiếp từ những tệp đang sử dụng trong dự án; các ảnh giao diện được chụp từ những đường dẫn chức năng tương ứng trên máy chủ cục bộ.' }),
];

for (const item of functions) {
  children.push(
    new Paragraph({ text: item.title, heading: HeadingLevel.HEADING_1 }),
    new Paragraph({ text: item.description }),
    new Paragraph({ text: 'ẢNH GIAO DIỆN', heading: HeadingLevel.HEADING_2 }),
    new Paragraph({ text: item.interfaceCaption }),
    imageParagraph(path.join(assets, item.interfaceImage)),
    new Paragraph({ text: 'ẢNH MÃ NGUỒN TƯƠNG ỨNG', heading: HeadingLevel.HEADING_2 }),
    new Paragraph({ text: `${item.codeCaption} Tệp nguồn: ${item.codeFile}.` }),
    imageParagraph(item.codeImage, 700, 460),
    new Paragraph({ text: ' ' }),
  );
}

children.push(
  new Paragraph({ text: '6. Kiến trúc và tổ chức mã nguồn', heading: HeadingLevel.HEADING_1 }),
  new Paragraph({ text: 'Hệ thống được tổ chức theo mô hình MVC. Router trong index.php tiếp nhận đường dẫn và điều hướng đến controller; controller xử lý luồng nghiệp vụ; model thực hiện truy vấn cơ sở dữ liệu; view trình bày dữ liệu cho người dùng. Cách tổ chức này giúp mã nguồn có tính phân tách, thuận lợi cho kiểm thử và bảo trì.' }),
  new Paragraph({ text: '7. Kết quả kiểm thử', heading: HeadingLevel.HEADING_1 }),
  new Paragraph({ text: 'Ứng dụng đã được khởi động bằng PHP XAMPP và kiểm tra các route chính. Trang danh sách sản phẩm trả về HTTP 200; các màn hình quản lý sản phẩm, danh mục, giỏ hàng và đặt hàng được chụp lại làm minh chứng trong báo cáo.' }),
  new Paragraph({ text: '8. Kết luận', heading: HeadingLevel.HEADING_1 }),
  new Paragraph({ text: 'Các chức năng cốt lõi của website bán hàng đã được triển khai và liên kết thành một quy trình thống nhất. Việc trình bày song song ảnh giao diện với ảnh mã nguồn giúp minh họa cụ thể cách từng chức năng được hiện thực trong dự án.' }),
);

const doc = new Document({ sections: [{ properties: {}, children }] });
Packer.toBuffer(doc).then((buffer) => {
  const output = path.join(root, 'report', 'webbanhang-report.docx');
  fs.writeFileSync(output, buffer);
  console.log(`CREATED ${output}`);
  console.log(`SIZE ${fs.statSync(output).size}`);
}).catch((error) => {
  console.error(error);
  process.exit(1);
});
