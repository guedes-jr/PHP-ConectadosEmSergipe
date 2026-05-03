from PIL import Image
import os

def resize_image(input_path, output_dir):
    if not os.path.exists(output_dir):
        os.makedirs(output_dir)
    
    img = Image.open(input_path)
    
    # Tamanhos comuns para favicon e ícones do Windows
    # Favicon: 16x16, 32x32, 48x48
    # Windows Start: 24x24, 32x32, 48x48, 256x256
    sizes = [16, 24, 32, 48, 64, 128, 256]
    
    generated_files = []
    
    for size in sizes:
        resized_img = img.resize((size, size), Image.Resampling.LANCZOS)
        output_path = os.path.join(output_dir, f"icon_{size}x{size}.png")
        resized_img.save(output_path)
        generated_files.append(output_path)
        print(f"Gerado: {output_path}")
    
    # Criar um arquivo .ico contendo múltiplos tamanhos
    ico_path = os.path.join(output_dir, "favicon.ico")
    img.save(ico_path, format='ICO', sizes=[(16, 16), (32, 32), (48, 48), (64, 64)])
    generated_files.append(ico_path)
    print(f"Gerado: {ico_path}")

if __name__ == "__main__":
    input_file = "/home/ubuntu/upload/pasted_file_OIWssC_conectadoem_sergipe_white.png"
    output_directory = "/home/ubuntu/icons"
    resize_image(input_file, output_directory)
