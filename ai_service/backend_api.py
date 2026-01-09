import os
import uvicorn
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import google.generativeai as genai
from typing import Optional
import json

# --- Configuration ---
API_KEY = os.environ.get("GEMINI_API_KEY", "AIzaSyCwaNXBaEwwgFV6YFTlLZhITbaJ2BQvcAA")

# ตั้งค่า Gemini - using gemini-2.5-flash (stable and has good quota)
genai.configure(api_key=API_KEY)
model = genai.GenerativeModel('gemini-2.5-flash')

# สร้าง App FastAPI
app = FastAPI(title="ObounERP AI Service")

# Add CORS middleware for Laravel to call
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# --- Mock Database ---
MOCK_INVENTORY = [
    {"id": 101, "name": "Paracetamol 500mg (Tylenol)", "stock": 450, "unit": "แผง", "price": 15, "expire": "12/2025", "category": "ยาแก้ปวด"},
    {"id": 102, "name": "Amoxicillin 500mg", "stock": 24, "unit": "กล่อง", "price": 85, "expire": "06/2024", "category": "ยาฆ่าเชื้อ"},
    {"id": 103, "name": "Cetirizine (Zyrtec)", "stock": 120, "unit": "แผง", "price": 25, "expire": "08/2025", "category": "ยาแก้แพ้"},
    {"id": 104, "name": "Vitamin C 1000mg", "stock": 5, "unit": "ขวด", "price": 250, "expire": "01/2026", "category": "วิตามิน"},
    {"id": 105, "name": "Ibuprofen 400mg (Gofen)", "stock": 80, "unit": "แผง", "price": 120, "expire": "10/2025", "category": "ยาแก้ปวด/อักเสบ"},
    {"id": 106, "name": "Simethicone (Air-X)", "stock": 200, "unit": "แผง", "price": 30, "expire": "03/2026", "category": "ยาแก้ท้องอืด"}
]

MOCK_SALES_TODAY = {
    "date": "2026-01-08",
    "total_sales": 14580,
    "bill_count": 42,
    "top_products": ["Mask N95", "Vitamin C", "Paracetamol"]
}

# --- Data Models ---
class ChatRequest(BaseModel):
    message: str
    user_id: Optional[str] = "guest"
    user_name: Optional[str] = "Staff"
    store_name: Optional[str] = "Oboun ERP"

class ChatResponse(BaseModel):
    reply: str
    status: str = "success"

# --- System Prompt ---
def get_system_prompt(store_name: str, user_name: str):
    return f"""
คุณเป็น AI Assistant ชื่อ "Oboun AI" ผู้ช่วยอัจฉริยะสำหรับระบบ ERP ร้านขายยา "{store_name}"

คุณจะช่วยเหลือพนักงาน "{user_name}" ในเรื่องต่างๆ ดังนี้:

1. **ความรู้ด้านยาและเวชภัณฑ์**:
   - ข้อมูลยา วิธีใช้ ขนาดยา ข้อห้ามใช้ ผลข้างเคียง
   - ปฏิกิริยาระหว่างยา (Drug Interactions)
   - ข้อควรระวังสำหรับผู้ป่วยกลุ่มพิเศษ (เด็ก ผู้สูงอายุ หญิงตั้งครรภ์)

2. **การใช้งานระบบ ERP**:
   - วิธีการขายสินค้าผ่าน POS
   - การจัดการสต็อกและการสั่งซื้อ
   - การดูรายงานและสถิติ
   - การจัดการลูกค้าและสมาชิก

3. **ข้อมูลสินค้าในร้าน (Real-time)**:
{json.dumps(MOCK_INVENTORY, ensure_ascii=False, indent=2)}

4. **ข้อมูลยอดขายวันนี้**:
{json.dumps(MOCK_SALES_TODAY, ensure_ascii=False, indent=2)}

กฎสำคัญ:
- **ตอบตามภาษาที่ผู้ใช้ถาม**: ถ้าผู้ใช้ถามเป็นภาษาไทย ให้ตอบเป็นภาษาไทย / If user asks in English, respond in English
- ตอบสั้น กระชับ ได้ใจความ
- ถ้าไม่แน่ใจในข้อมูลยา แนะนำให้ปรึกษาเภสัชกร
- ห้ามให้คำแนะนำทางการแพทย์ที่อาจเป็นอันตราย
- ใช้ emoji อย่างเหมาะสมเพื่อให้การสนทนาเป็นมิตร
- หากพบยาหมดอายุ (ปีปัจจุบัน 2026) ให้แจ้งเตือนทันที
"""

# --- Endpoints ---

@app.get("/")
def health_check():
    return {"status": "running", "service": "ObounERP AI Backend"}

@app.post("/chat", response_model=ChatResponse)
async def chat_with_ai(request: ChatRequest):
    try:
        prompt = request.message
        system_instruction = get_system_prompt(request.store_name, request.user_name)
        
        # ส่งคำสั่งไปที่ Gemini
        response = model.generate_content(
            contents=[system_instruction + "\n\nUser: " + prompt]
        )
        
        return ChatResponse(reply=response.text)

    except Exception as e:
        print(f"Error: {e}")
        raise HTTPException(status_code=500, detail=str(e))

# --- Run Server ---
if __name__ == "__main__":
    print("🚀 Starting ObounERP AI Backend on port 8001...")
    uvicorn.run(app, host="0.0.0.0", port=8001)
