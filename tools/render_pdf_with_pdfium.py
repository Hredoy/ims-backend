import sys
from pathlib import Path

import pypdfium2 as pdfium


def main():
    if len(sys.argv) != 3:
        raise SystemExit("Usage: render_pdf_with_pdfium.py PDF_PATH OUTPUT_DIR")

    pdf_path = Path(sys.argv[1])
    out_dir = Path(sys.argv[2])
    out_dir.mkdir(parents=True, exist_ok=True)

    pdf = pdfium.PdfDocument(str(pdf_path))
    for index in range(len(pdf)):
        page = pdf[index]
        image = page.render(scale=3).to_pil()
        out_path = out_dir / f"page-{index + 1}.png"
        image.save(out_path)
        print(out_path)


if __name__ == "__main__":
    main()
