import os
import sys
from options import generate_backend, generate_view, generate_fullstack

# Cross-platform key reading
if os.name == "nt":  # Windows
    import msvcrt
    def get_key():
        return msvcrt.getch().decode("utf-8")
else:  # Unix-based systems
    import tty
    import termios
    def get_key():
        fd = sys.stdin.fileno()
        old_settings = termios.tcgetattr(fd)
        try:
            tty.setraw(fd)
            first_char = sys.stdin.read(1)
            if first_char == '\x1b':  # Escape sequence
                next_chars = sys.stdin.read(2)  # Read the next two characters
                return first_char + next_chars
            else:
                return first_char
        finally:
            termios.tcsetattr(fd, termios.TCSADRAIN, old_settings)

def menu():
    logo = [
        "\033[94m_________  ______    ____  __ __\033[0m",
        "\033[94m\____ \  \/ /    \  / ___||  |  |\033[0m",
        "\033[94m|  |_> >   /   |  \/ /_/  |  |  /\033[0m",
        "\033[94m|   __/ \_/|___|  /\___  /|____/ \033[0m",
        "\033[94m|__|            \//_____/        \033[0m",
    ]

    menu_items = [
        "Generate Backend Components 🛠️",
        "Generate Frontend Components 🖥️",
        "Generate Fullstack Components 📦 All in Once!!!",
        "Exit 🚪"
    ]

    current_idx = 0

    while True:
        clear()

        # Display the logo
        for line in logo:
            print(line)
        print("\n")

        # Display the menu items with highlighting for the selected one
        for idx, item in enumerate(menu_items):
            if idx == current_idx:
                print(f"> {item}")  # Highlight the selected item
            else:
                print(f"  {item}")

        print("\nUse ↑/↓ to navigate and Enter to select. Press Esc to exit.")

        key = get_key()

        if key in ["\x1b[A", "w"]:  # Up arrow or 'w' key
            if current_idx > 0:
                current_idx -= 1
        elif key in ["\x1b[B", "s"]:  # Down arrow or 's' key
            if current_idx < len(menu_items) - 1:
                current_idx += 1
        elif key in ["\r", "\n"]:  # Enter key
            if current_idx == 0:
                generate_backend()
                break
            elif current_idx == 1:
                generate_view()
                break
            elif current_idx == 2:
                generate_fullstack()
                break
            elif current_idx == 3:
                break  # Exit the menu
        elif key == "\x1b":  # Escape key
            break

def clear():
    os.system('cls' if os.name=='nt' else 'clear')


if __name__ == "__main__":
    menu()