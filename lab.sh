#!/bin/bash

# Kiểm tra hành động truyền vào (check hoặc run)
ACTION=$1
THU_MUC=$(pwd)
PORT=8103 # Bạn có thể đổi số cổng tùy ý (ví dụ: 8101, 8102,...)

if [ "$ACTION" == "check" ]; then
    echo "MOI_TRUONG=SAN_GANG"
    echo "THU_MUC=$THU_MUC"
    echo "Kiem tra PHP:"
    php -v
    if [ $? -eq 0 ]; then
        echo "=> PHP da san sang."
    else
        echo "=> Chua cai dat PHP!"
    fi
elif [ "$ACTION" == "run" ]; then
    echo "MOI_TRUONG=SAN_GANG"
    echo "THU_MUC=$THU_MUC"
    DIA_CHI="http://127.0.0.1:$PORT"
    echo "DIA_CHI=$DIA_CHI"
    echo "Giu terminal nay mo. Nhan Ctrl+C de dung; khong co tien trinh chay nen."
    php -S 127.0.0.1:$PORT
else
    echo "Su dung: bash lab.sh check hoac bash lab.sh run"
fi