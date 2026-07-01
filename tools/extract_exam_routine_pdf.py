import sys
from pathlib import Path

import pdfplumber

if hasattr(sys.stdout, "reconfigure"):
    sys.stdout.reconfigure(encoding="utf-8")


def main():
    if len(sys.argv) != 2:
        raise SystemExit("Usage: extract_exam_routine_pdf.py PDF_PATH")

    pdf_path = Path(sys.argv[1])
    with pdfplumber.open(pdf_path) as pdf:
        print(f"pages {len(pdf.pages)}")
        for index, page in enumerate(pdf.pages, start=1):
            print(f"---PAGE {index}---")
            print(page.extract_text() or "")
            tables = page.extract_tables()
            for table_index, table in enumerate(tables, start=1):
                print(f"---TABLE {index}.{table_index}---")
                for row in table:
                    print("\t".join("" if cell is None else cell.replace("\n", " ") for cell in row))


if __name__ == "__main__":
    main()
