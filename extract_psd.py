from psd_tools import PSDImage
import os

psd = PSDImage.open('Ancardia.psd')
os.makedirs('web/assets/img/psd', exist_ok=True)

def extract_layers(layer, path="web/assets/img/psd"):
    if layer.is_group():
        for child in layer:
            extract_layers(child, path)
    else:
        if layer.has_pixels():
            name = layer.name.replace('/', '_').replace('\\', '_')
            filename = f"{path}/{name}.png"
            # handle duplicates
            counter = 1
            while os.path.exists(filename):
                filename = f"{path}/{name}_{counter}.png"
                counter += 1
            layer.composite().save(filename)
            print(f"Saved: {filename}")

extract_layers(psd)
