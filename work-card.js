document.addEventListener("DOMContentLoaded", () => {
    // すべての作品カードに対して処理を行う
    const workCards = document.querySelectorAll(".work-card");

    workCards.forEach((card) => {
        // 各カード内の要素を取得
        const title = card.querySelector(".work-title"); // 各カードのタイトル
        const button = card.querySelector(".expand-button"); // 各カードの展開ボタン
        const titleLine = title.parentElement; // タイトルの親要素（幅を計算するために使用）
        const copyButton = card.querySelector(".copy-button"); // コピー機能ボタン

        // --- コピー機能の管理 ---
        copyButton.addEventListener("click", () => {
            const workCode = card.querySelector(".work-code").innerText; // コピー対象のコード
            if (navigator.clipboard) { // Clipboard APIが利用可能かチェック
                navigator.clipboard.writeText(workCode).then(() => {
                    copyButton.classList.remove("default");
                    copyButton.classList.add("success");

                    setTimeout(() => {
                        copyButton.classList.remove("success");
                        copyButton.classList.add("default");
                    }, 1500);
                }).catch((err) => {
                    alert("コピーに失敗しました: " + err);
                });
            } else {
                alert("このブラウザはコピー機能をサポートしていません");
            }
        });

        // --- タイトル展開ボタンの表示を制御 ---
        const updateButtonVisibility = () => {
            const titleWidth = title.scrollWidth; // タイトルの実際の幅
            const titleLineWidth = titleLine.offsetWidth; // タイトルの親要素の幅
            const buttonWidth = button.offsetWidth || 16; // ボタンの幅（デフォルト値: 16px）

            // タイトルが表示エリアに収まりきらない場合、ボタンを表示
            if (titleWidth + buttonWidth > titleLineWidth) {
                button.style.display = "inline-block";
            } else {
                button.style.display = "none";
            }
        };

        // --- タイトル展開ボタンのクリックイベント ---
        button.addEventListener("click", () => {
            const isCollapsed = title.style.whiteSpace === "nowrap" || title.style.whiteSpace === "";

            if (isCollapsed) {
                title.style.whiteSpace = "normal"; // 折りたたみを解除
                button.textContent = "▲"; // ボタンの表示を変更
                button.setAttribute("aria-label", "タイトルを折りたたむ");
            } else {
                title.style.whiteSpace = "nowrap"; // 折りたたみ
                button.textContent = "▼"; // ボタンの表示を変更
                button.setAttribute("aria-label", "タイトルを展開");
            }
        });

        // --- 初期化処理 ---
        updateButtonVisibility();
        window.addEventListener("resize", updateButtonVisibility);
    });
});
