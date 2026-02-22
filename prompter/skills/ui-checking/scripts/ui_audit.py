import os
import re
import sys

def audit_blade_file(filepath):
    with open(filepath, 'r') as f:
        content = f.read()

    issues = []

    # 1. Missing wire:key in loops
    # Look for @foreach and check if next line or block contains wire:key
    foreach_matches = re.finditer(r'@foreach\s*\(.*?\)', content)
    for match in foreach_matches:
        # Check the next 10 lines for wire:key
        start = match.end()
        context = content[start:start+500]
        if 'wire:key' not in context:
            line_no = content.count('\n', 0, match.start()) + 1
            issues.append(f"L{line_no}: Potential missing 'wire:key' in @foreach loop.")

    # 2. Skeuomorphic consistency: .glass-panel without blur
    glass_panels = re.finditer(r'class="[^"]*glass-panel[^"]*"', content)
    for panel in glass_panels:
        class_str = panel.group(0)
        if 'blur' not in class_str:
            line_no = content.count('\n', 0, panel.start()) + 1
            issues.append(f"L{line_no}: '.glass-panel' found without backdrop-blur classes.")

    # 3. Livewire model performance
    live_models = re.finditer(r'wire:model\.live', content)
    for model in live_models:
        line_no = content.count('\n', 0, model.start()) + 1
        issues.append(f"L{line_no}: 'wire:model.live' detected. Ensure this is necessary for real-time reactivity.")

    return issues

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python ui_audit.py <path_to_blade_file>")
        sys.exit(1)
    
    file_to_audit = sys.argv[1]
    if os.path.exists(file_to_audit):
        print(f"--- Auditing {file_to_audit} ---")
        results = audit_blade_file(file_to_audit)
        if results:
            for issue in results:
                print(f"[!] {issue}")
        else:
            print("[+] No common UI pitfalls found.")
    else:
        print(f"Error: File {file_to_audit} not found.")
